<?php

declare(strict_types=1);

namespace Lint\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\{Rule, RuleErrorBuilder};

/**
 * RULE COVERED
 * ------------
 * PHP.md 1.2 / PHP-NAMING-002 (REQUIRED)
 * "Use camelCase for functions, methods, properties and variables."
 * This rule covers parameters, promoted constructor properties included.
 * Function and method names are covered by CamelCapsMethodNameRule.
 *
 * WHY A CUSTOM RULE
 * -----------------
 * Same gap as PropertyNamingRule: no tool in the stack renames or inspects
 * identifiers.
 *
 * CEILING (deliberate simplification)
 * -----------------------------------
 * Case only. Closures and arrow functions are checked too (their parameter
 * names are the author's choice). Boolean names (PHP.md 1.5) are not checked,
 * for the reason given in PropertyNamingRule.
 *
 * Identifier: naming.parameterName
 *
 * @implements Rule<FunctionLike>
 */
class ParameterNamingRule implements Rule
{
    public function getNodeType(): string {
        return FunctionLike::class;
    }

    public function processNode(Node $node, Scope $scope): array {
        $errors = [];

        foreach ($node->getParams() as $param) {
            if (! $param->var instanceof Variable || ! is_string($param->var->name)) {
                continue;
            }

            $name = $param->var->name;
            if (! preg_match('/^[a-z][a-zA-Z0-9]*$/', $name)) {
                $errors[] = RuleErrorBuilder::message(sprintf(
                    'Parameter "$%s" is not in lowerCamelCase format (PHP-NAMING-002).',
                    $name,
                ))
                    ->identifier('naming.parameterName')
                    ->line($param->getStartLine())
                    ->build();
            }
        }

        return $errors;
    }
}
