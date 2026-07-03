// ----------------------------------------------------------------------------
// POLICY — Project rules take precedence over this template
// ----------------------------------------------------------------------------
// 1. This file is a TEMPLATE. Each project's own agreed rules always take
//    priority over the defaults below.
// 2. If a rule does not fit the project for a valid reason, decide it at the 
//    beginning of the project: adjust/disable the rule HERE (in this config) with
//    a short comment explaining WHY, so the decision is visible and reviewable.
//    Example:
//      // PROJECT DECISION (2026-07-06): logger is not used in this CLI tool,
//      // console output IS the product → no-console disabled.
//      'no-console': 'off',
//
// ============================================================================
// ESLint flat config template (ESLint 9+) — BWV NodeJs Coding Rules
// ============================================================================
// Mapping with NodeJs.md: each rule is annotated as `// <ID> — <rule title>`,
// matching the Table of Contents of NodeJs.md.
// Severity convention: REQUIRED → 'error', RECOMMENDED → 'warn'.
//
// Copy to project root as `eslint.config.mjs`. Keep LAYER 0/1/2/6; enable 3/4/5 per stack.
// Install: npm i -D eslint @eslint/js typescript-eslint eslint-plugin-vue
//          eslint-plugin-simple-import-sort eslint-plugin-jsdoc
//          eslint-config-prettier globals
// Order matters: later layers override earlier ones (LAYER 6 + prettier must stay last).
// ============================================================================

import pluginJs from '@eslint/js';
import configPrettier from 'eslint-config-prettier';
import pluginJsdoc from 'eslint-plugin-jsdoc';
import pluginSort from 'eslint-plugin-simple-import-sort';
import pluginVue from 'eslint-plugin-vue';
import globals from 'globals';
import tseslint from 'typescript-eslint';

// ----------------------------------------------------------------------------
// Naming convention entries — cover the following rules:
//   1.1 — Use camelCase for variables, functions, methods and object properties
//   1.4 — Use boolean names that describe state or capability
//   1.6 — Use PascalCase for classes, interfaces, type aliases and enums
//   1.7 — Use UPPER_CASE only for module-level constants
//   1.8 — Do not use _ to mark private fields (use TS `private` instead)
// NOTE: naming-convention options are REPLACED (not merged) between config
// objects, so shared entries are declared here and spread into each layer
// to keep them from drifting apart.
// ----------------------------------------------------------------------------

/** Entries usable for every file (no type information required). */
const NAMING_BASE = [
  // 1.1 (exception) — destructured variables take their names from external
  // shapes (API responses, libraries), so camelCase is not enforced.
  {
    selector: 'variable',
    modifiers: ['destructured'],
    format: ['camelCase', 'PascalCase', 'UPPER_CASE'],
  },
  // 1.7 — Use UPPER_CASE only for module-level constants
  // (only module-level (global) const may be UPPER_CASE; camelCase stays valid).
  {
    selector: 'variable',
    modifiers: ['const', 'global'],
    format: ['UPPER_CASE', 'camelCase'],
  },
  // 1.1 — Use camelCase for variables (including local const, per 1.7).
  { selector: 'variable', format: ['camelCase'] },
  // 1.1 — Use camelCase for functions and methods.
  {
    selector: ['function', 'classMethod'],
    format: ['camelCase'],
  },
  // 1.1 — Use camelCase for parameters.
  // 1.8 — `_` is acceptable for intentionally unused parameters
  // (kept in sync with argsIgnorePattern of no-unused-vars in LAYER 6).
  { selector: 'parameter', format: ['camelCase'], leadingUnderscore: 'allow' },
  // 1.6 — Use PascalCase for classes, interfaces, type aliases and enums
  // (including enum members, per the rule's example).
  {
    selector: ['class', 'interface', 'typeAlias', 'enum'],
    format: ['PascalCase'],
  },
  { selector: 'enumMember', format: ['PascalCase'] },
  // 1.1 — object properties: NOT enforced by default because they often must
  // match external API/DB shapes (snake_case…). Enable the entry below if the
  // project controls every object shape:
  // { selector: 'objectLiteralProperty', format: ['camelCase'] },
];

/** 1.8 — Do not use `_` to mark private members. */
const PRIVATE_MEMBER_NAMING = [
  // `private _x` — members declared `private` with a `_` prefix.
  {
    selector: ['classProperty', 'classMethod'],
    modifiers: ['private'],
    format: ['camelCase'],
    leadingUnderscore: 'forbid',
  },
  // `_x` used instead of `private`. classProperty only — `_` functions and
  // methods already fail NAMING_BASE camelCase at error level.
  // format: null skips the format check; leadingUnderscore still runs.
  {
    selector: 'classProperty',
    format: null,
    leadingUnderscore: 'forbid',
  },
];

/** Boolean naming entries that require type information (LAYER 3 only). */
const NAMING_TYPE_AWARE = [
  // 1.4 (exception) — destructured booleans keep external names: no prefix
  // warning (format: null). NAMING_BASE camelCase still applies at error level.
  {
    selector: 'variable',
    modifiers: ['destructured'],
    types: ['boolean'],
    format: null,
  },
  // 1.4 (exception) — clear state adjectives, plain (`loading`), compound
  // (`pageLoading`) or UPPER_CASE (`FEATURE_ENABLED`). Extend per project.
  {
    selector: 'variable',
    types: ['boolean'],
    filter: {
      regex: '^(?:[a-z][a-zA-Z0-9]*)?(?:Enabled|Disabled|Visible|Loading|Found|Ok)$|^(?:enabled|disabled|visible|loading|found|ok)$|^(?:[A-Z][A-Z0-9]*_)*(?:ENABLED|DISABLED|VISIBLE|LOADING|FOUND|OK)$',
      match: true,
    },
    format: ['camelCase', 'UPPER_CASE'],
  },
  // 1.7 — boolean module-level constants may still be UPPER_CASE
  // (this entry is more specific than the 1.4 entry below, so it matches first).
  {
    selector: 'variable',
    modifiers: ['const', 'global'],
    types: ['boolean'],
    format: ['UPPER_CASE', 'PascalCase'],
    prefix: [
      'is', 'should', 'can', 'has', 'have', 'need', 'needs',
      'was', 'were', 'will', 'would', 'do', 'does', 'did',
      'IS_', 'SHOULD_', 'CAN_', 'HAS_',
    ],
  },
  // 1.4 — Use boolean names that describe state or capability.
  // How naming-convention works: the prefix is trimmed, then the remainder is
  // checked against the PascalCase format (e.g. isConnected → Connected ✓).
  {
    selector: 'variable',
    types: ['boolean'],
    format: ['PascalCase'],
    prefix: [
      'is', 'should', 'can', 'has', 'have', 'need', 'needs',
      'was', 'were', 'will', 'would', 'do', 'does', 'did',
    ],
  },
];

/** @type {import('eslint').Linter.Config[]} */
export default [
  // ==========================================================================
  // LAYER 0 — Ignores (add your build dirs)
  // ==========================================================================
  {
    ignores: [
      '**/.nuxt/**',
      '**/.output/**',
      '**/.turbo/**',
      '**/dist/**',
      '**/build/**',
      '**/coverage/**',
      '**/node_modules/**'
    ],
  },

  // ==========================================================================
  // LAYER 1 — BASE / shared rules (always keep)
  // ==========================================================================
  {
    files: ['**/*.{js,mjs,cjs,ts,tsx,vue}'],
    plugins: {
      'simple-import-sort': pluginSort,
      jsdoc: pluginJsdoc,
      'bw-private-naming': tseslint.plugin,
    },
    settings: {
      // Types live in TS annotations, not in JSDoc tags
      jsdoc: { mode: 'typescript' },
    },
    rules: {
      // 4.6 — sort-import (auto-fixable)
      'simple-import-sort/imports': 'error',
      'simple-import-sort/exports': 'error',
      'no-duplicate-imports': 'error',

      // 1.9 — Use const or let instead of var
      // 4.5 — no-var and prefer-const
      'no-var': 'error',
      'prefer-const': 'error',

      // 1.1 / 1.6 / 1.7 — REQUIRED naming conventions.
      '@typescript-eslint/naming-convention': ['error', ...NAMING_BASE],

      // 1.8 — Do not use _ to mark private members (RECOMMENDED).
      'bw-private-naming/naming-convention': [
        'warn',
        ...PRIVATE_MEMBER_NAMING,
      ],

      // 4.3 — no-console (utility scripts are relaxed in LAYER 5b)
      'no-console': 'error',

      // 4.4 — no-debugger
      'no-debugger': 'error',

      // 4.9 — Maximum number of lines per file
      // (1000 lines of code, excluding blank lines/comments)
      'max-lines': ['error', { max: 1000, skipBlankLines: true, skipComments: true }],

      // 4.11 — Function complexity (RECOMMENDED → warn):
      // functions > 50 lines should be split into sub-routines;
      // complexity 10 is a reasonable default threshold
      'max-lines-per-function': ['warn', { max: 50, skipBlankLines: true, skipComments: true }],
      complexity: ['warn', 10],

      // 4.13 — Early returns and guard clauses: no nesting deeper than 3 levels
      'max-depth': ['warn', 3],
      'no-else-return': 'warn',

      // 3.4 — JSDoc on exported/public functions and classes (publicOnly)
      'jsdoc/require-jsdoc': [
        'error',
        {
          publicOnly: true,
          require: {
            FunctionDeclaration: true,
            ClassDeclaration: true,
            ArrowFunctionExpression: true,
            FunctionExpression: true,
            MethodDefinition: false,
          },
        },
      ],
      // 3.4 — validate the content of JSDoc that IS written:
      // @param names must match the signature; tag names must be valid
      'jsdoc/check-param-names': 'error',
      'jsdoc/check-tag-names': 'error',
      // 3.4 — a JSDoc block must describe the purpose of each parameter and
      // of the return value (these fire only on functions that have JSDoc)
      'jsdoc/require-param': 'error',
      'jsdoc/require-param-description': 'error',
      'jsdoc/require-returns': 'error',
      'jsdoc/require-returns-description': 'error',

      // 2.1 — Let Prettier handle formatting rules (semi, quotes, indent…).
      // Do NOT add formatting rules to ESLint; eslint-config-prettier at the
      // end of this file disables every rule that conflicts with Prettier.
    },
  },

  // ==========================================================================
  // LAYER 2 — Recommended presets (always keep)
  // ==========================================================================
  { languageOptions: { globals: { ...globals.browser, ...globals.node } } },
  pluginJs.configs.recommended,
  ...tseslint.configs.recommended,

  // ==========================================================================
  // LAYER 3 — Type-aware rules (TS only; needs tsconfig + projectService)
  // ==========================================================================
  {
    files: ['**/*.{ts,tsx}'],
    plugins: {
      // Plugin alias for the boolean naming convention.
      'bw-boolean-naming': tseslint.plugin,
    },
    languageOptions: {
      parserOptions: { projectService: true },
    },
    rules: {
      // 1.1 / 1.6 / 1.7 — REQUIRED naming conventions.
      '@typescript-eslint/naming-convention': [
        'error',
        ...NAMING_BASE,
      ],

      // 1.4 — Boolean predicate prefixes are preferred, not required.
      'bw-boolean-naming/naming-convention': [
        'warn',
        ...NAMING_TYPE_AWARE,
      ],

      // 2.3 — Declare return types for exported or complex functions
      // (only flags exported/public functions; local functions may rely on inference)
      '@typescript-eslint/explicit-module-boundary-types': 'error',

      // 2.4 — Use optional chaining and nullish coalescing where appropriate
      '@typescript-eslint/prefer-optional-chain': 'error',
      '@typescript-eslint/prefer-nullish-coalescing': 'error',

      // 4.10 — no-floating-promises
      '@typescript-eslint/no-floating-promises': 'error',

      // 4.12 — Parameter object pattern (RECOMMENDED → warn). TS-only:
      // the required fix (a typed parameter object) is TypeScript.
      'max-params': ['warn', 3],

      // 2.2 — Type function parameters explicitly: enforced by tsconfig
      // (`"strict": true` / `"noImplicitAny": true`), not by ESLint.
    },
  },

  // ==========================================================================
  // LAYER 4 — Vue (delete for plain Node/TS)
  // ==========================================================================
  ...pluginVue.configs['flat/essential'],
  {
    files: ['**/*.vue'],
    languageOptions: {
      parserOptions: { parser: tseslint.parser, ecmaFeatures: { jsx: true } },
    },
    rules: {
      'vue/multi-word-component-names': 'error',
      'vue/one-component-per-file': 'error',
    },
  },

  // ==========================================================================
  // LAYER 5 — Per-context relaxations
  // ==========================================================================
  // 5a — Test files: relax size + naming (test data often uses PascalCase const)
  {
    files: ['**/*.test.ts', '**/*.spec.ts', '**/__tests__/**/*.{ts,tsx,js}'],
    rules: {
      // 4.9 / 4.11 / 4.12 / 2.3 — size, complexity, parameter object pattern
      // and return-type rules are off for tests
      'max-lines': 'off',
      'max-lines-per-function': 'off',
      'max-params': 'off',
      '@typescript-eslint/explicit-module-boundary-types': 'off',
      'bw-boolean-naming/naming-convention': 'off',
      'bw-private-naming/naming-convention': 'off',
      // 3.4 — tests document themselves through their descriptions
      'jsdoc/require-jsdoc': 'off',
      '@typescript-eslint/naming-convention': [
        'error',
        {
          selector: 'variable',
          modifiers: ['const'],
          format: ['UPPER_CASE', 'camelCase', 'PascalCase'],
        },
      ],
    },
  },
  // 5b — 4.3 no-console (exception): utility/dev scripts (migrations, seeds)
  // may use console, as allowed by the rule itself
  {
    files: ['**/scripts/**', '**/migrations/**', '**/seeds/**'],
    rules: {
      'no-console': 'off',
    },
  },

  // ==========================================================================
  // LAYER 6 — Final overrides (must stay last; wins over recommended presets)
  // ==========================================================================
  {
    rules: {
      // 4.2 — no-unused-vars
      // 1.8 — `_` marks intentionally unused variables/parameters
      '@typescript-eslint/no-unused-vars': [
        'error',
        {
          argsIgnorePattern: '^_',
          varsIgnorePattern: '^_',
          caughtErrorsIgnorePattern: '^_',
        },
      ],
      // 4.7 — no-explicit-any (RECOMMENDED → warn,
      // overrides the 'error' from tseslint recommended)
      '@typescript-eslint/no-explicit-any': 'warn',
      // 2.7 — no-non-null-assertion (RECOMMENDED → warn). In LAYER 6 so
      // 'warn' survives tseslint.configs.strict, which sets it to 'error'.
      '@typescript-eslint/no-non-null-assertion': 'warn',
      'no-undef': 'off', // TS handles this; avoid false positives
      'no-unexpected-multiline': 'off',
    },
  },

  // 2.1 — Let Prettier handle formatting rules
  // MUST BE LAST: disables every formatting rule that conflicts with Prettier
  configPrettier,
];
