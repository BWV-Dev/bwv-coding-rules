<?php

// ----------------------------------------------------------------------------
// POLICY — Project rules take precedence over this template
// ----------------------------------------------------------------------------
// 1. This file is a TEMPLATE. Each project's own agreed rules always take
//    priority over the defaults below.
// 2. If a rule does not fit the project for a valid reason, decide it at the
//    beginning of the project: adjust/disable the rule HERE (in this config)
//    with a short comment explaining WHY, so the decision is visible and
//    reviewable. Example:
//      // PROJECT DECISION (2026-08-26): legacy views are formatted by hand
//      // and reformatting them would break the blame history.
//      ->notPath('resources/views/legacy')
//
// ============================================================================
// PHP-CS-Fixer config template — BWV PHP Coding Rules (PHP >= 8.2)
// ============================================================================
// Mapping with PHP.md: each fixer is annotated as `// <ID> — <rule title>`,
// matching the Table of Contents of PHP.md.
//
// Copy to project root as `.php-cs-fixer.dist.php`.
// Install: composer require --dev friendsofphp/php-cs-fixer
// Run:     composer lint / composer lint:fix
// ============================================================================

// ----------------------------------------------------------------------------
// Files to format — adjust to your project layout (Laravel defaults below).
// ----------------------------------------------------------------------------
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude([
        'bootstrap/cache',
        'node_modules',
        'public',
        'storage',
        'vendor',
    ])
    ->notName([
        '*.blade.php',
        '_ide_helper.php',
        '_ide_helper_actions.php',
        '_ide_helper_models.php',
        '.phpstorm.meta.php',
    ])
    ->name('*.php');

return (new PhpCsFixer\Config())
    ->setFinder($finder)
    // Risky fixers can change runtime behaviour — see the OPTIONAL block at the
    // end of this file before switching this to true.
    ->setRiskyAllowed(false)
    ->setRules([
        // ====================================================================
        // BASE — PSR-12 coding standard (keep first, everything below overrides it)
        // Note: '@PER-CS' (the successor of PSR-12) can be used instead once the
        // whole team is on a PHP-CS-Fixer version that ships it.
        // ====================================================================
        '@PSR12' => true,

        // ====================================================================
        // 2.1 — Let PHP-CS-Fixer handle formatting rules
        // ====================================================================
        // Arrays
        'array_indentation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'list_syntax' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_whitespace_before_comma_in_array' => true,
        'trim_array_spaces' => true,
        'whitespace_after_comma_in_array' => ['ensure_single_space' => true],
        'no_spaces_around_offset' => true,
        // Trailing comma in multi-line arrays / arguments / parameters / match
        'trailing_comma_in_multiline' => [
            'elements' => [
                'arguments',
                'arrays',
                'match',
                'parameters',
            ],
        ],
        'no_trailing_comma_in_singleline' => true,

        // Strings & quotes
        'single_quote' => true,
        'explicit_string_variable' => true,
        'simple_to_complex_string_variable' => true,
        'explicit_indirect_variable' => true,
        'concat_space' => ['spacing' => 'one'],

        // Operators & spacing
        'binary_operator_spaces' => true,
        'unary_operator_spaces' => true,
        'not_operator_with_successor_space' => true,
        'object_operator_without_whitespace' => true,
        'cast_spaces' => true,
        'lowercase_cast' => true,
        'no_short_bool_cast' => true,
        'no_unset_cast' => true,
        'types_spaces' => true,
        // Replaces the deprecated 'function_typehint_space'
        'type_declaration_spaces' => true,
        'single_space_around_construct' => true,
        'operator_linebreak' => ['only_booleans' => true],
        'standardize_not_equals' => true,

        // Statements & structure
        'no_multiple_statements_per_line' => true,
        'semicolon_after_instruction' => true,
        'space_after_semicolon' => ['remove_in_empty_for_expressions' => true],
        'no_singleline_whitespace_before_semicolons' => true,
        'multiline_whitespace_before_semicolons' => true,
        'no_empty_statement' => true,
        'statement_indentation' => true,
        'method_chaining_indentation' => true,
        // 2.6 — Use curly braces for all flow control statements
        'control_structure_braces' => true,
        'control_structure_continuation_position' => true,
        // Replaces the deprecated 'curly_braces_position'
        'braces_position' => ['functions_opening_brace' => 'same_line'],
        'single_line_empty_body' => true,
        // 4.1 — Early returns and guard clauses
        'no_superfluous_elseif' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,

        // Imports — sorted alphabetically, grouped per namespace, no unused ones
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'single_import_per_statement' => false,
        'group_import' => true,
        'global_namespace_import' => true,
        'no_unused_imports' => true,
        'no_leading_namespace_whitespace' => true,
        'clean_namespace' => true,
        'fully_qualified_strict_types' => true,

        // Casing
        'class_reference_name_casing' => true,
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        'native_function_casing' => true,
        // Replaces the deprecated 'native_function_type_declaration_casing'
        'native_type_declaration_casing' => true,

        // Misc formatting
        'echo_tag_syntax' => ['format' => 'short'],
        'no_mixed_echo_print' => true,
        'new_with_parentheses' => ['anonymous_class' => false],
        'include' => true,
        'linebreak_after_opening_tag' => true,
        'single_class_element_per_statement' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'curly_brace_block',
                'extra',
                'parenthesis_brace_block',
                'square_brace_block',
                'throw',
                'use',
            ],
        ],

        // ====================================================================
        // 2.2 — Class layout
        // ====================================================================
        'class_attributes_separation' => true,
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'case',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
                'magic',
                'phpunit',
                'method_public',
                'method_protected',
                'method_private',
            ],
        ],

        // ====================================================================
        // 2.4 — Declare strict types and type declarations
        // (`declare(strict_types=1)` itself is risky to add automatically —
        //  see the OPTIONAL block at the end of this file)
        // ====================================================================
        'nullable_type_declaration_for_default_null_value' => true,
        'no_null_property_initialization' => true,

        // ====================================================================
        // 2.7 — Blank line rules inside a function
        // Add 'if', 'for', 'foreach', 'while', 'switch', 'try' to the list below
        // to also enforce the blank line AFTER each control structure.
        // ====================================================================
        'blank_line_before_statement' => ['statements' => ['return']],

        // ====================================================================
        // 2.9 — Use nullsafe and null coalescing operators
        // ====================================================================
        'ternary_to_null_coalescing' => true,
        'assign_null_coalescing_to_coalesce_equal' => true,

        // ====================================================================
        // 3.2 / 3.3 — Single-line and multi-line comments
        // ====================================================================
        'single_line_comment_style' => ['comment_types' => ['hash']],
        'single_line_comment_spacing' => true,
        'align_multiline_comment' => true,
        'multiline_comment_opening_closing' => true,
        'no_empty_comment' => true,

        // ====================================================================
        // 3.4 — PHPDoc comments (types stay in the signature, not in the tags)
        // ====================================================================
        'no_superfluous_phpdoc_tags' => [
            'allow_mixed' => true,
            'remove_inheritdoc' => false,
        ],
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_phpdoc' => true,
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_indent' => true,
        'phpdoc_line_span' => true,
        'phpdoc_order' => true,
        'phpdoc_separation' => true,
        'phpdoc_trim' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types' => true,
        'phpdoc_types_order' => [
            'null_adjustment' => 'always_last',
            'sort_algorithm' => 'none',
        ],
        'phpdoc_var_annotation_correct_order' => true,
        'phpdoc_var_without_name' => true,

        // ====================================================================
        // OPTIONAL — risky fixers (they can change runtime behaviour)
        // Enable ONLY on a new project, or after running the full test suite,
        // and set ->setRiskyAllowed(true) above.
        // ====================================================================
        // 2.4 — adds `declare(strict_types=1);` to every file
        // 'declare_strict_types' => true,
        // 2.8 — Type-Safe Comparisons: rewrites == / != to === / !==
        // 'strict_comparison' => true,
        // 2.8 — forces the strict flag of in_array(), array_search(), array_keys()
        // 'strict_param' => true,
    ])
    ->setLineEnding("\n");
