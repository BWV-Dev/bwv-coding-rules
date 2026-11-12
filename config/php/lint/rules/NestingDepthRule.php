<?php

declare(strict_types=1);

namespace Lint\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\{ArrowFunction, Closure};
use PhpParser\Node\{FunctionLike, Stmt};
use PHPStan\Analyser\Scope;
use PHPStan\Rules\{Rule, RuleErrorBuilder};

/**
 * RULE COVERED
 * ------------
 * PHP.md 4.2 / PHP-USAGE-002 (RECOMMENDED)
 * "Avoid hand-rolled nested logic — look for a built-in function (in_array,
 * array_filter, array_column, str_contains, ...) or a `match` expression
 * instead."
 * Only nesting DEPTH is measured; whether a built-in or `match` could replace
 * the logic is not statically checkable.
 *
 * WHY A CUSTOM RULE
 * -----------------
 * Rector's earlyReturn set rewrites *some* shapes (if/else with a return), but
 * it cannot see depth as such. This rule measures depth and never rewrites.
 *
 * CEILING (deliberate simplification)
 * -----------------------------------
 * Counts nesting of control-flow statements only (if/else/elseif, loops,
 * switch, try/catch; `match` is an expression and is not counted). The limit
 * is `$maxDepth` = 2: a third level is reported, at the line of the FIRST
 * statement that exceeds the limit (not the function declaration line). Only
 * one finding per function. The standard gives no number. Closure and arrow
 * function bodies are not checked, neither on their own nor as part of the
 * enclosing function.
 *
 * Identifier: usage.nestingDepth
 *
 * @implements Rule<FunctionLike>
 */
class NestingDepthRule implements Rule
{
    public function __construct(private int $maxDepth = 2) {}

    public function getNodeType(): string {
        return FunctionLike::class;
    }

    public function processNode(Node $node, Scope $scope): array {
        if ($node instanceof Closure || $node instanceof ArrowFunction) {
            return [];
        }

        $statements = $node->getStmts();
        if ($statements === null) {
            return [];
        }

        $line = 0;
        $deepest = $this->deepest($statements, 0, $line);
        if ($deepest <= $this->maxDepth) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Control flow nests %d levels deep, over the limit of %d; flatten it with guard clauses (PHP-USAGE-002).',
                $deepest,
                $this->maxDepth,
            ))
                ->identifier('usage.nestingDepth')
                ->line($line)
                ->build(),
        ];
    }

    /**
     * @param array<Node> $nodes
     * @param int $line set to the start line of the first node over the limit
     */
    private function deepest(array $nodes, int $depth, int &$line): int {
        $deepest = $depth;
        foreach ($nodes as $node) {
            // A closure gets its own FunctionLike visit, so do not descend into it.
            if ($node instanceof Closure || $node instanceof ArrowFunction) {
                continue;
            }

            $childDepth = $this->isNesting($node) ? $depth + 1 : $depth;
            $deepest = max($deepest, $childDepth);
            if ($line === 0 && $childDepth > $this->maxDepth) {
                $line = $node->getStartLine();
            }

            foreach ($node->getSubNodeNames() as $name) {
                $child = $node->{$name};
                $children = is_array($child) ? $child : [$child];
                $children = array_values(array_filter($children, static fn ($c): bool => $c instanceof Node));
                if ($children !== []) {
                    $deepest = max($deepest, $this->deepest($children, $childDepth, $line));
                }
            }
        }

        return $deepest;
    }

    private function isNesting(Node $node): bool {
        // Else_/ElseIf_ are sub-nodes of If_ — counting them too would report
        // a plain if/else as two levels.
        return $node instanceof Stmt\If_
            || $node instanceof Stmt\For_
            || $node instanceof Stmt\Foreach_
            || $node instanceof Stmt\While_
            || $node instanceof Stmt\Do_
            || $node instanceof Stmt\Switch_
            || $node instanceof Stmt\TryCatch;
    }
}
