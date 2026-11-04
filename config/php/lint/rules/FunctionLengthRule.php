<?php

declare(strict_types=1);

namespace Lint\Rules;

use PhpParser\Node\Expr\{ArrowFunction, Closure};
use PhpParser\Node\FunctionLike;
use PhpParser\{Node, NodeFinder};
use PHPStan\Analyser\Scope;
use PHPStan\Rules\{Rule, RuleErrorBuilder};

/**
 * RULE COVERED
 * ------------
 * CLEAN-FUNC-SIZE (RECOMMENDED), a rule of the code review standard
 * (CODING_STANDARDS.md) with no counterpart in PHP.md:
 * "Do not judge a function by line count alone. [...] For long functions
 * (more than 50 lines), consider extracting sub-routines with descriptive
 * names."
 *
 * WHY A CUSTOM RULE
 * -----------------
 * No tool in the stack counts function bodies. PHPStan's own complexity-ish
 * rules are type-driven, not size-driven.
 *
 * CEILING (deliberate simplification)
 * -----------------------------------
 * Counts physical lines of the body (signature and closing brace excluded),
 * not complexity — the standard itself says not to judge by line count alone,
 * which no static tool can do. Long-but-flat data maps (a `match` table, a
 * `rules()` array) therefore trip this rule; that is the accepted
 * false-positive cost of a cheap deterministic check.
 *
 * Lines inside a closure/arrow function are NOT counted, and closures are not
 * checked as functions of their own. Reason: a Laravel migration's `up()` is
 * `Schema::create(..., function (Blueprint $table) { 30 columns })` — a
 * schema declaration, not 30 lines of logic, and counting it made every
 * migration in a clean scaffold fail. The accepted cost is the mirror case: a
 * genuinely long function whose logic lives inside a closure body (a big
 * `Route::group(...)`) is not reported.
 *
 * Identifier: cleanCode.functionLength
 *
 * @implements Rule<FunctionLike>
 */
class FunctionLengthRule implements Rule
{
    public function __construct(private int $maxLines = 50) {}

    public function getNodeType(): string {
        return FunctionLike::class;
    }

    public function processNode(Node $node, Scope $scope): array {
        if ($node instanceof Closure || $node instanceof ArrowFunction) {
            return [];
        }

        $statements = $node->getStmts();
        if ($statements === null || $statements === []) {
            return [];
        }

        $first = $statements[0]->getStartLine();
        $last = $statements[count($statements) - 1]->getEndLine();
        $length = $last - $first + 1 - $this->closureLines($statements);
        if ($length <= $this->maxLines) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Function body is %d lines (closures excluded), over the limit of %d; extract sub-routines (CLEAN-FUNC-SIZE).',
                $length,
                $this->maxLines,
            ))
                ->identifier('cleanCode.functionLength')
                ->line($node->getStartLine())
                ->build(),
        ];
    }

    /**
     * Distinct physical lines occupied by closure bodies inside $statements.
     *
     * @param array<Node> $statements
     */
    private function closureLines(array $statements): int {
        $finder = new NodeFinder();
        $lines = [];

        foreach ($statements as $statement) {
            $closures = $finder->find(
                $statement,
                static fn (Node $n): bool => $n instanceof Closure || $n instanceof ArrowFunction,
            );

            foreach ($closures as $closure) {
                // Inside of the closure only: its first and last line still
                // belong to the enclosing call.
                for ($line = $closure->getStartLine() + 1; $line < $closure->getEndLine(); $line++) {
                    $lines[$line] = true;
                }
            }
        }

        return count($lines);
    }
}
