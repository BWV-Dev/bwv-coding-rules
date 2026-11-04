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
 * PHP.md 2.3 / PHP-STYLE-003 (RECOMMENDED)
 * "Prefer a maximum line length of 80 characters. Wrap a longer line after a
 * comma and before an operator. Do not split a long string, URL or fully
 * qualified class name just to fit."
 *
 * WHY A CUSTOM RULE
 * -----------------
 * PHP-CS-Fixer has no line-length rule at all (it cannot know where a long
 * expression may be broken safely), and PHPStan ships none.
 *
 * CEILING (deliberate simplification)
 * -----------------------------------
 * Limit is the standard's 80.
 * Exempt (the standard says not to split these):
 *   - `use ...;` lines;
 *   - lines containing a URL (`://`);
 *   - comment/docblock lines that exceed the limit only because of ONE
 *     unbreakable token (FQCN, URL, path);
 *   - lines whose only excess is a long whitespace-free quoted string literal.
 * Known gaps: a bare FQCN/long identifier in CODE (not comment, not quoted) is
 * still reported; a comment that is long because of many words is reported.
 *
 * FileNode is a PHPStan virtual node that fires exactly once per analysed file,
 * including files with no namespace (Laravel config/*.php, routes/*.php).
 *
 * Identifier: style.lineLength
 *
 * @implements Rule<FileNode>
 */
class LineLengthRule implements Rule
{
    public function __construct(private int $maxLineLength = 80) {}

    public function getNodeType(): string {
        return FileNode::class;
    }

    public function processNode(Node $node, Scope $scope): array {
        $file = $scope->getFile();
        if (! file_exists($file)) {
            return [];
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            return [];
        }

        $errors = [];
        foreach ($lines as $index => $line) {
            $length = mb_strlen($line);
            if ($length > $this->maxLineLength && ! $this->isUnbreakable($line, $length)) {
                $lineNumber = $index + 1;
                $errors[] = RuleErrorBuilder::message(
                    sprintf('Line exceeds maximum limit of %d characters; contains %d characters (PHP-STYLE-003).', $this->maxLineLength, $length),
                )
                    ->identifier('style.lineLength')
                    ->file($file)
                    ->line($lineNumber)
                    ->build();
            }
        }

        return $errors;
    }

    /**
     * True when the line is long only because of text the standard says not to split.
     */
    private function isUnbreakable(string $line, int $length): bool {
        $trimmed = ltrim($line);
        if (preg_match('/^use\s/', $trimmed) === 1 || str_contains($line, '://')) {
            return true;
        }

        // Comment line: drop the longest token; if the rest fits, one token caused it.
        if (preg_match('~^(?:/\*+|\*|//|#(?!\[))~', $trimmed) === 1) {
            $tokens = preg_split('/\s+/', trim($line), -1, PREG_SPLIT_NO_EMPTY) ?: [];
            $longest = 0;
            foreach ($tokens as $token) {
                $longest = max($longest, mb_strlen($token));
            }

            return $length - $longest <= $this->maxLineLength;
        }

        // Long quoted literal with no whitespace inside (path, FQCN, key): cannot be wrapped.
        if (preg_match_all('/\'(?:[^\'\\\\]|\\\\.)*\'|"(?:[^"\\\\]|\\\\.)*"/', $line, $matches) > 0) {
            foreach ($matches[0] as $literal) {
                if (preg_match('/\s/', $literal) !== 1 && $length - mb_strlen($literal) <= $this->maxLineLength) {
                    return true;
                }
            }
        }

        return false;
    }
}
