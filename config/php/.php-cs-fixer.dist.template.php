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
//
// `__DIR__` assumes this file has already been copied to the PROJECT ROOT as
// `.php-cs-fixer.dist.php`. Running it while it still sits in `config/php/`
// would scan the wrong directory.
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
    // Risky fixers are ENABLED. There are exactly three of them here
    // ('declare_strict_types', 'strict_comparison', 'strict_param') and between
    // them they implement two REQUIRED rules that cannot be enforced any other
    // way: 2.4 `declare(strict_types=1)` and both halves of 2.8 (`===` / strict
    // flags). They can change runtime behaviour, so on an existing codebase run
    // `composer lint` (dry-run) and read the diff BEFORE the first
    // `composer lint:fix` — see the LEGACY OPT-OUT block at the end of this file.
    ->setRiskyAllowed(true)
    ->setRules([
        // ====================================================================
        // BASE — PSR-12 coding standard (keep first, everything below overrides it)
        // Note: '@PER-CS' (the successor of PSR-12) can be used instead once the
        // whole team is on a PHP-CS-Fixer version that ships it.
        //
        // Two deliberate deviations from PSR-12 (both documented in PHP.md 2.1):
        //   #1 'braces_position'            — `{` of a function/method stays on
        //                                     the signature line.
        //   #2 'single_import_per_statement' — group imports `use A\{B, C};`
        //                                     are allowed.
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
        // 2.3 — Prefer a maximum line length
        // The ONLY fixer backing 2.3, and it does less than the name suggests:
        // it never introduces a line break, it just moves a `&&` / `||` that
        // sits at the end of a line down to the start of the next one. Deciding
        // where to break stays manual, as does every non-boolean operator.
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
        // PSR-12 DEVIATION #1 — PSR-12 puts `{` on its own line for functions
        // and methods; BWV keeps it on the signature line. Classes/interfaces
        // are left on the PSR-12 default (`{` on the next line).
        'braces_position' => ['functions_opening_brace' => 'same_line'],
        'single_line_empty_body' => true,
        // 4.1 — Early returns and guard clauses
        'no_superfluous_elseif' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,

        // Imports — sorted alphabetically, grouped per namespace, no unused ones
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        // PSR-12 DEVIATION #2 — '@PSR12' forces one import per statement; BWV
        // groups them per namespace instead: `use App\Models\{Invoice, User};`
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
        // Configured explicitly — the defaults do not match rule 2.2:
        //   trait_import: default 'none' DELETES the blank line after the
        //                 `use SomeTrait;` block that 2.2 requires.
        //   case:         default is already 'none'; spelled out so nobody
        //                 "fixes" enum cases into a blank-line-separated list.
        //   const/property: 'one' separates EVERY const and EVERY property, not
        //                 just the groups. The fixer has no "separate groups
        //                 only" mode ('only_if_meta' removes the group blank
        //                 line as well), so 2.2 documents the airy form.
        'class_attributes_separation' => [
            'elements' => [
                'trait_import' => 'one',
                'case' => 'none',
                'const' => 'one',
                'property' => 'one',
                'method' => 'one',
            ],
        ],
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
        // ====================================================================
        // RISKY — adds `declare(strict_types=1);` to every file. On an existing
        // codebase this turns silent type juggling into TypeError at runtime.
        'declare_strict_types' => true,
        'nullable_type_declaration_for_default_null_value' => true,
        'no_null_property_initialization' => true,

        // ====================================================================
        // 2.7 — Blank line rules inside a function
        // ====================================================================
        // Only 'return' is enabled below, so 2.7 is covered by halves:
        // "1 blank line before `return`" is enforced, "1 blank line after each
        // if/loop block" is NOT — it stays a code-review item.
        //
        // The reason is that this fixer inserts a blank line BEFORE a listed
        // statement, which cannot express "after a block". Adding 'if', 'for',
        // 'foreach', 'while', 'switch' and 'try' to the list would catch only
        // the case of a block followed by another control structure, and still
        // miss a block followed by anything else (e.g. a plain assignment) —
        // half a rule, at the cost of surprising diffs. Left off deliberately.
        'blank_line_before_statement' => ['statements' => ['return']],

        // ====================================================================
        // 2.8 — Type-Safe Comparisons
        // ====================================================================
        // RISKY — both rewrite behaviour, not just layout:
        //   'strict_comparison' turns == / != into === / !==
        //   'strict_param' forces the strict flag of in_array(), array_search()
        //                  and array_keys()
        // Code that relied on loose comparison ('1' == 1) will change result.
        'strict_comparison' => true,
        'strict_param' => true,

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
        // 'phpdocs_like' (not the 'phpdocs_only' default) so the `*` of a plain
        // `/* ... */` block comment is aligned too — that is the comment style
        // rule 3.3 is about.
        'align_multiline_comment' => ['comment_type' => 'phpdocs_like'],
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
        // LEGACY OPT-OUT — turning the risky fixers back off
        // ====================================================================
        // The three risky fixers above ('declare_strict_types',
        // 'strict_comparison', 'strict_param') implement REQUIRED rules and are
        // ON by default. A new project should keep them.
        //
        // An existing codebase without enough test coverage may not survive them
        // in one step. Before deciding, look at the damage:
        //
        //     composer lint          # dry-run + diff, changes nothing
        //
        // If it is too large to review, disable them AT THE START of the project
        // — comment out the three fixers above, set ->setRiskyAllowed(false),
        // and record the decision here so it stays visible and reviewable:
        //
        //     // PROJECT DECISION (2026-08-31): legacy codebase relies on loose
        //     // comparison in the billing module; enabling strict_comparison
        //     // needs the regression suite finished first (#123456).
        //
        // Rules 2.4 and 2.8 then move to the manual code-review checklist.
        // Re-enable them once the test suite can back the change.
    ])
    // Rule 2.1 — 4 spaces, never tabs; LF line endings.
    ->setIndent('    ')
    ->setLineEnding("\n");
