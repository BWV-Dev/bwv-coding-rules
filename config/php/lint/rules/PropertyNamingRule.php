<?php

declare(strict_types=1);

namespace Lint\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\{Rule, RuleErrorBuilder};

/**
 * RULE COVERED
 * ------------
 * PHP.md 1.2 / PHP-NAMING-002 (REQUIRED)
 * "Use camelCase for functions, methods, properties and variables."
 * This rule covers declared properties; a promoted constructor property is a
 * parameter and is covered by ParameterNamingRule.
 *
 * WHY A CUSTOM RULE
 * -----------------
 * PHP-CS-Fixer never renames symbols (a rename is not a style fix), Rector's
 * rename rules need an explicit old->new map, and PHPStan has no naming rules.
 *
 * CEILING (deliberate simplification)
 * -----------------------------------
 * Case only. Type-prefixed names ($strName; PHP.md 1.4) are valid camelCase
 * and are not detected. Boolean names (PHP.md 1.5) are not checked either:
 * 1.5 accepts adjectives (`$enabled`, `$visible`, `$active`), which cannot be
 * told apart from a bad name without judgement.
 * Exempt: CakePHP's underscore-prefixed protected properties `$_accessible`,
 * `$_hidden`, `$_virtual` (framework convention). Any other `_name` property
 * is still reported.
 *
 * Identifier: naming.propertyName
 *
 * @implements Rule<Property>
 */
class PropertyNamingRule implements Rule
{
    public function getNodeType(): string {
        return Property::class;
    }

    public function processNode(Node $node, Scope $scope): array {
        $errors = [];
        foreach ($node->props as $prop) {
            $name = $prop->name->toString();
            if (in_array($name, ['_accessible', '_hidden', '_virtual'], true)) {
                continue;
            }

            if (! preg_match('/^[a-z][a-zA-Z0-9]*$/', $name)) {
                $errors[] = RuleErrorBuilder::message(sprintf(
                    'Property "$%s" is not in lowerCamelCase format (PHP-NAMING-002).',
                    $name,
                ))
                    ->identifier('naming.propertyName')
                    ->line($prop->getStartLine())
                    ->build();
            }
        }

        return $errors;
    }
}
