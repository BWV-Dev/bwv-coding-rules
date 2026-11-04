<?php

declare(strict_types=1);

namespace Lint\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\{ClassLike, EnumCase, Enum_};
use PHPStan\Analyser\Scope;
use PHPStan\Rules\{Rule, RuleErrorBuilder};

/**
 * RULE COVERED
 * ------------
 * PHP.md 1.1 / PHP-NAMING-001 (REQUIRED)
 * "Use PascalCase (UpperCamelCase) for class files, namespaces, classes,
 * interfaces, enums, traits and enum cases. The name should be a noun."
 *
 * WHY A CUSTOM RULE
 * -----------------
 * Nothing in the stack checks the case of a name: PHP-CS-Fixer never renames
 * a symbol, and PHPStan has no naming rules.
 *
 * CEILING (deliberate simplification)
 * -----------------------------------
 * Covers every named class-like declaration (class, interface, trait, enum);
 * anonymous classes have no name to check. Enum cases are checked too
 * (same identifier). Namespaces and the "should be a noun" half are not
 * checked and stay a review item; under PSR-4 autoloading the file name
 * follows the class name.
 *
 * Identifier: naming.validClassName
 *
 * @implements Rule<ClassLike>
 */
class ValidClassNameRule implements Rule
{
    public function getNodeType(): string {
        return ClassLike::class;
    }

    public function processNode(Node $node, Scope $scope): array {
        $named = $node->name === null ? [] : [$node];
        if ($node instanceof Enum_) {
            foreach ($node->stmts as $stmt) {
                if ($stmt instanceof EnumCase) {
                    $named[] = $stmt;
                }
            }
        }

        $errors = [];
        foreach ($named as $item) {
            $name = $item->name->toString();
            if (preg_match('/^[A-Z][a-zA-Z0-9]*$/', $name) === 1) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(sprintf(
                'Name "%s" is not in UpperCamelCase format (PHP-NAMING-001).',
                $name,
            ))
                ->identifier('naming.validClassName')
                ->line($item->getStartLine())
                ->build();
        }

        return $errors;
    }
}
