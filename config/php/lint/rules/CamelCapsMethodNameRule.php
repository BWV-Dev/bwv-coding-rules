<?php

declare(strict_types=1);

namespace Lint\Rules;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\{ClassMethod, Function_};
use PHPStan\Analyser\Scope;
use PHPStan\Rules\{Rule, RuleErrorBuilder};

/**
 * RULE COVERED
 * ------------
 * PHP.md 1.2 / PHP-NAMING-002 (REQUIRED)
 * "Use camelCase for functions, methods, properties and variables."
 * This rule covers METHODS and global FUNCTIONS. Properties, parameters and
 * variables are covered by PropertyNamingRule / ParameterNamingRule /
 * VariableNamingRule.
 *
 * WHY A CUSTOM RULE
 * -----------------
 * PHP-CS-Fixer never renames a symbol (a rename is not a style fix), Rector's
 * rename rules need an explicit old->new map, and PHPStan has no naming rules.
 *
 * CEILING (deliberate simplification)
 * -----------------------------------
 * Checks the case only. Whether the name starts with a verb (PHP.md 1.6) is
 * not checkable statically and stays a review item.
 * CakePHP Entity mutators/accessors `_setX()` / `_getX()` are skipped
 * (framework convention); other `_name` methods are still reported.
 * `test_*` methods are skipped: snake_case test names are PHPUnit's own
 * convention and what `laravel new` generates, so enforcing camelCase there
 * only produces findings nobody acts on.
 *
 * Identifier: naming.camelCapsMethodName
 *
 * @implements Rule<FunctionLike>
 */
class CamelCapsMethodNameRule implements Rule
{
    public function getNodeType(): string {
        return FunctionLike::class;
    }

    public function processNode(Node $node, Scope $scope): array {
        // Closures and arrow functions have no name to check.
        if (! $node instanceof ClassMethod && ! $node instanceof Function_) {
            return [];
        }

        $methodName = $node->name->toString();
        // Skip magic methods (__construct, __destruct, etc.) and PHPUnit's
        // snake_case test methods.
        if (str_starts_with($methodName, '__') || str_starts_with($methodName, 'test_')
            || preg_match('/^_(set|get)[A-Z][a-zA-Z0-9]*$/', $methodName) === 1
        ) {
            return [];
        }

        if (! preg_match('/^[a-z][a-zA-Z0-9]*$/', $methodName)) {
            if ($node instanceof Function_) {
                $message = sprintf(
                    'Function name "%s" is not in lowerCamelCase format (PHP-NAMING-002).',
                    $methodName,
                );
            } else {
                $classReflection = $scope->getClassReflection();
                $className = $classReflection ? $classReflection->getName() : '';
                $message = sprintf(
                    'Method name "%s::%s" is not in lowerCamelCase format (PHP-NAMING-002).',
                    $className,
                    $methodName,
                );
            }

            return [
                RuleErrorBuilder::message($message)
                    ->identifier('naming.camelCapsMethodName')
                    ->line($node->getStartLine())
                    ->build(),
            ];
        }

        return [];
    }
}
