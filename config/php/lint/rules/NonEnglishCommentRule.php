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
 * PHP.md 3.5 / PHP-COMMENT-005 (REQUIRED)
 * "USE English for comments." PHP-COMMENT-005 allows Japanese for business
 * terms, configuration values, labels or domain-specific names, enclosed in
 * quotes if possible.
 *
 * WHY A CUSTOM RULE
 * -----------------
 * PHP-CS-Fixer normalises comment *syntax* (`//` vs `#`, `/* *\/` blocks) but
 * never reads the text. Nothing else in the stack looks at comments at all.
 *
 * CEILING (deliberate simplification)
 * -----------------------------------
 * Flags accented Latin letters only — i.e. Vietnamese, French, Spanish prose.
 * CJK (Japanese/Chinese) is deliberately NOT flagged, because Japanese is
 * allowed for business terms, configuration values and labels
 * (`// 1:規格書`, `->comment('氏名')`), and telling a permitted label from
 * prose is not something a static check can do. Text inside quotes is stripped
 * before the check, so a quoted foreign label never trips it.
 *
 * Identifier: comment.language
 *
 * @implements Rule<FileNode>
 */
class NonEnglishCommentRule implements Rule
{
    /**
     * Latin-1 Supplement + Latin Extended-A/B + Vietnamese (Latin Extended
     * Additional). Deliberately excludes CJK, Hiragana and Katakana.
     */
    private const ACCENTED_LATIN = '/[\x{00C0}-\x{024F}\x{1E00}-\x{1EFF}]/u';

    public function getNodeType(): string {
        return FileNode::class;
    }

    public function processNode(Node $node, Scope $scope): array {
        $file = $scope->getFile();
        $code = @file_get_contents($file);
        if ($code === false) {
            return [];
        }

        $errors = [];
        foreach (token_get_all($code) as $token) {
            if (! is_array($token) || ! in_array($token[0], [T_COMMENT, T_DOC_COMMENT], true)) {
                continue;
            }

            // Quoted text is an explicitly allowed escape hatch for labels and
            // configuration values, so it must not count as prose.
            $patterns = ['/(\'|")(.*?)\1/u', '/[「『].*?[」』]/u'];
            $text = preg_replace($patterns, '', $token[1]) ?? $token[1];
            if (preg_match(self::ACCENTED_LATIN, $text) !== 1) {
                continue;
            }

            $errors[] = RuleErrorBuilder::message(
                'Comment is not written in English; translate it (PHP-COMMENT-005).',
            )
                ->identifier('comment.language')
                ->file($file)
                ->line($token[2])
                ->build();
        }

        return $errors;
    }
}
