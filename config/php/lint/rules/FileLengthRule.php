<?php

declare(strict_types=1);

namespace Lint\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\FileNode;
use PHPStan\Rules\{Rule, RuleErrorBuilder};

/**
 * RULE COVERED
 * ------------
 * PHP.md 4.6 / PHP-USAGE-006 (REQUIRED)
 * "Limit each file to a maximum of 1000 lines of code."
 *
 * WHY A CUSTOM RULE
 * -----------------
 * None of the three tools can express a file-length budget: PHP-CS-Fixer only
 * rewrites style inside a file, Rector rewrites code, and PHPStan has no
 * built-in line-count rule.
 *
 * CEILING (deliberate simplification)
 * -----------------------------------
 * Counts physical lines, comments and blank lines included. FileNode fires
 * exactly once per analysed file, including files with no namespace (Laravel
 * config/*.php, routes/*.php).
 *
 * Identifier: usage.fileLength
 *
 * @implements Rule<FileNode>
 */
class FileLengthRule implements Rule
{
    public function __construct(private int $maxLines = 1000) {}

    public function getNodeType(): string {
        return FileNode::class;
    }

    public function processNode(Node $node, Scope $scope): array {
        $file = $scope->getFile();
        $lines = @file($file, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            return [];
        }

        $count = count($lines);
        if ($count <= $this->maxLines) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'File has %d lines, over the limit of %d; split it by responsibility (PHP-USAGE-006).',
                $count,
                $this->maxLines,
            ))
                ->identifier('usage.fileLength')
                ->file($file)
                ->line(1)
                ->build(),
        ];
    }
}
