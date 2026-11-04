<?php

declare(strict_types=1);

namespace Lint\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\{Assign, Variable};
use PHPStan\Analyser\Scope;
use PHPStan\Rules\{Rule, RuleErrorBuilder};

/**
 * RULE COVERED
 * ------------
 * PHP.md 1.2 / PHP-NAMING-002 (REQUIRED)
 * "Use camelCase for functions, methods, properties and variables."
 * This rule covers local variables.
 *
 * WHY A CUSTOM RULE
 * -----------------
 * Completes 1.2: methods (CamelCapsMethodNameRule), properties and
 * parameters (PropertyNamingRule / ParameterNamingRule), and here local
 * variables. No tool in the stack inspects identifiers.
 *
 * CEILING (deliberate simplification)
 * -----------------------------------
 * Fires on assignment only (`$snake_case = ...`), not on every read of the
 * variable — one finding per offending assignment instead of one per usage.
 * A variable that is only ever read (e.g. an extracted template variable) or
 * only set by `foreach` / `list()` is not reported. Boolean names (PHP.md
 * 1.5) and type-prefixed names ($strName; PHP.md 1.4) are valid camelCase and
 * are not detected.
 *
 * Identifier: naming.variableName
 *
 * @implements Rule<Assign>
 */
class VariableNamingRule implements Rule
{
    public function getNodeType(): string {
        return Assign::class;
    }

    public function processNode(Node $node, Scope $scope): array {
        if (! $node->var instanceof Variable || ! is_string($node->var->name)) {
            return [];
        }

        $name = $node->var->name;
        // `$this` and PHP's superglobals are not the author's to rename.
        if ($name === 'this' || str_starts_with($name, '_')) {
            return [];
        }

        if (preg_match('/^[a-z][a-zA-Z0-9]*$/', $name) === 1) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Variable "$%s" is not in lowerCamelCase format (PHP-NAMING-002).',
                $name,
            ))
                ->identifier('naming.variableName')
                ->line($node->getStartLine())
                ->build(),
        ];
    }
}
