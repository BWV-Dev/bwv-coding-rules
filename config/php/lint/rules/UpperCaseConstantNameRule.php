<?php

declare(strict_types=1);

namespace Lint\Rules;

use PhpParser\Node;
use PhpParser\Node\{Arg, Name, Stmt};
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\{ClassConst, Const_, Expression};
use PHPStan\Analyser\Scope;
use PHPStan\Rules\{Rule, RuleErrorBuilder};

/**
 * RULE COVERED
 * ------------
 * PHP.md 1.3 / PHP-NAMING-003 (REQUIRED)
 * "Use UPPER_CASE_UNDERSCORE for class constants and global constants."
 *
 * WHY A CUSTOM RULE
 * -----------------
 * No built-in in the stack. (symplify/phpstan-rules ships an equivalent
 * `UppercaseConstantRule`, but pulling that package in for one rule means
 * adopting its whole ruleset — this file is the cheaper half.)
 *
 * CEILING (deliberate simplification)
 * -----------------------------------
 * Class constants, global `const` and `define()` with a literal name. A
 * `define()` whose name is built at runtime is not checked. Enum cases are
 * PascalCase (PHP.md 1.1) and are checked by ValidClassNameRule.
 *
 * Identifier: naming.upperCaseConstantName
 *
 * @implements Rule<Stmt>
 */
class UpperCaseConstantNameRule implements Rule
{
    public function getNodeType(): string {
        return Stmt::class;
    }

    public function processNode(Node $node, Scope $scope): array {
        $names = [];
        if ($node instanceof ClassConst || $node instanceof Const_) {
            foreach ($node->consts as $const) {
                $names[] = [$const->name->toString(), $const->getStartLine()];
            }
        } elseif (
            $node instanceof Expression
            && $node->expr instanceof FuncCall
            && $node->expr->name instanceof Name
            && $node->expr->name->toLowerString() === 'define'
            && ($node->expr->args[0] ?? null) instanceof Arg
            && $node->expr->args[0]->value instanceof String_
        ) {
            $names[] = [$node->expr->args[0]->value->value, $node->getStartLine()];
        }

        $errors = [];
        foreach ($names as [$constName, $line]) {
            if (preg_match('/^[A-Z][A-Z0-9_]*$/', $constName) !== 1) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Constant "%s" is not in UPPER_CASE_UNDERSCORE format (PHP-NAMING-003).',
                        $constName,
                    ),
                )
                    ->identifier('naming.upperCaseConstantName')
                    ->line($line)
                    ->build();
            }
        }

        return $errors;
    }
}
