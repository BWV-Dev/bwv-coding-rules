<?php

declare(strict_types=1);

namespace Lint\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Echo_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\{Rule, RuleErrorBuilder};

/**
 * RULE COVERED
 * ------------
 * WebSecurityRules.md 5 / SEC-XSS-001 (CRITICAL)
 * "Use context-aware output encoding to prevent XSS." Laravel: use Blade
 * `{{ }}`. CakePHP: output is not escaped automatically, so escape it with
 * h().
 *   Bad (CakePHP): <?= $userName; ?>      Good: <?= h($userName); ?>
 *
 * WHY A CUSTOM RULE
 * -----------------
 * No coverage in the stack; XSS is CRITICAL. This rule catches the PHP half of
 * the standard — an `echo` (including the `<?= ?>` short form, which parses to
 * the same node) of a non-literal that is not wrapped in an escaper. `print`
 * is an expression, not this node type, and is not checked.
 *
 * CEILING (deliberate simplification)
 * -----------------------------------
 * Each part of an echoed concatenation is inspected at its outermost call
 * only, so `echo strtoupper(h($name));` IS reported although it is escaped
 * (false positive), and so is `echo trim($name);` (helper not in the
 * allowlist). Blade templates are out of reach: PHPStan does not parse
 * `.blade.php`, so `{!! $x !!}` cannot be detected here — that half of
 * SEC-XSS-001 stays a review item. CakePHP `templates/` is outside the
 * PHPStan `paths:` of the template; add it there to check `<?= ?>` in views.
 * Safe: string literals, h (CakePHP), e (Laravel), htmlspecialchars,
 * htmlentities, esc_html, strip_tags, urlencode, (int)/(float)/(bool) casts,
 * and any value PHPStan already knows is an int, float or bool.
 *
 * Identifier: security.unescapedOutput
 *
 * @implements Rule<Echo_>
 */
class UnescapedOutputRule implements Rule
{
    /**
     * @var list<string>
     */
    private const ESCAPERS = ['h', 'e', 'htmlspecialchars', 'htmlentities', 'esc_html', 'strip_tags', 'urlencode'];

    public function getNodeType(): string {
        return Echo_::class;
    }

    public function processNode(Node $node, Scope $scope): array {
        $errors = [];
        foreach ($node->exprs as $expr) {
            foreach ($this->flatten($expr) as $part) {
                if ($this->isSafe($part, $scope)) {
                    continue;
                }

                $errors[] = RuleErrorBuilder::message(
                    'Echoed value is not HTML-escaped; wrap it in h() (CakePHP) / e() (Laravel) or use {{ }} in a template (SEC-XSS-001).',
                )
                    ->identifier('security.unescapedOutput')
                    ->line($expr->getStartLine())
                    ->build();

                break;
            }
        }

        return $errors;
    }

    /**
     * @return list<Expr>
     */
    private function flatten(Expr $expr): array {
        if ($expr instanceof Concat) {
            return array_merge($this->flatten($expr->left), $this->flatten($expr->right));
        }

        return [$expr];
    }

    private function isSafe(Expr $expr, Scope $scope): bool {
        if ($expr instanceof String_) {
            return true;
        }

        // A numeric cast or a value PHPStan already knows is int/float/bool
        // cannot carry markup.
        if ($expr instanceof Expr\Cast\Int_ || $expr instanceof Expr\Cast\Double || $expr instanceof Expr\Cast\Bool_) {
            return true;
        }

        $type = $scope->getType($expr);
        if ($type->isInteger()->yes() || $type->isFloat()->yes() || $type->isBoolean()->yes()) {
            return true;
        }

        return $expr instanceof FuncCall
            && $expr->name instanceof Node\Name
            && in_array(strtolower($expr->name->getLast()), self::ESCAPERS, true);
    }
}
