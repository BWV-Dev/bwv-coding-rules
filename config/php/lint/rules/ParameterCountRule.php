<?php

declare(strict_types=1);

namespace Lint\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\{ArrowFunction, Closure};
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\{Rule, RuleErrorBuilder};

/**
 * RULE COVERED
 * ------------
 * CLEAN-PARAMS (RECOMMENDED), a rule of the code review standard
 * (CODING_STANDARDS.md) with no counterpart in PHP.md:
 * "Functions with more than three arguments should take a typed parameter
 * object: a DTO / value object. Do not use an associative array as the
 * parameter object."
 * Only the count is checked; an `array $data` parameter object is not
 * detected.
 *
 * WHY A CUSTOM RULE
 * -----------------
 * Pure counting on the signature; no tool in the stack does it.
 *
 * CEILING (deliberate simplification)
 * -----------------------------------
 * Constructors are exempt: constructor property promotion is the idiomatic
 * way to build a DTO, and penalising it would push people back to setters.
 * Closures/arrow functions are exempt — their signature is dictated by the
 * caller (framework callbacks), not by the author.
 *
 * Identifier: cleanCode.parameterCount
 *
 * @implements Rule<FunctionLike>
 */
class ParameterCountRule implements Rule
{
    public function __construct(private int $maxParameters = 3) {}

    public function getNodeType(): string {
        return FunctionLike::class;
    }

    public function processNode(Node $node, Scope $scope): array {
        if ($node instanceof Closure || $node instanceof ArrowFunction) {
            return [];
        }

        if ($node instanceof ClassMethod && $node->name->toLowerString() === '__construct') {
            return [];
        }

        $count = count($node->getParams());
        if ($count <= $this->maxParameters) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Function takes %d parameters, over the limit of %d; group them into a DTO (CLEAN-PARAMS).',
                $count,
                $this->maxParameters,
            ))
                ->identifier('cleanCode.parameterCount')
                ->line($node->getStartLine())
                ->build(),
        ];
    }
}
