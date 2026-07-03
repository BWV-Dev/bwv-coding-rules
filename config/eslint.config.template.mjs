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
  // 1.8 — `_` is only acceptable for unused parameters, never on
  // functions/methods (without 'forbid', naming-convention silently trims
  // the leading underscore before checking the format).
  {
    selector: ['function', 'classMethod'],
    format: ['camelCase'],
    leadingUnderscore: 'forbid',
  },
  // 1.1 — Use camelCase for parameters.
  // 1.8 — `_` is acceptable for intentionally unused parameters
  // (kept in sync with argsIgnorePattern of no-unused-vars in LAYER 6).
  { selector: 'parameter', format: ['camelCase'], leadingUnderscore: 'allow' },
  // 1.8 — Do not use _ to mark private members (fields and methods);
  // use TypeScript `private` instead.
  {
    selector: ['classProperty', 'classMethod'],
    modifiers: ['private'],
    format: ['camelCase'],
    leadingUnderscore: 'forbid',
  },
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

/** Entries that require type information (LAYER 3 only, with projectService). */
const NAMING_TYPE_AWARE = [
  // 1.4 (exception) — destructured booleans take their names from external
  // shapes, so no prefix is enforced. This entry has both modifiers + types,
  // making it the most specific; it must come first to override the boolean
  // entry below (format: null = skip the check).
  {
    selector: 'variable',
    modifiers: ['destructured'],
    types: ['boolean'],
    format: null,
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

      // 1.1 / 1.4 / 1.6 / 1.7 / 1.8 — Naming (see NAMING_BASE for details;
      // this version needs no type info; TS files get REPLACED by the fuller
      // version in LAYER 3)
      '@typescript-eslint/naming-convention': ['error', ...NAMING_BASE],

      // 2.7 — Avoid non-null assertion unless there is a clear reason
      // (RECOMMENDED → warn)
      '@typescript-eslint/no-non-null-assertion': 'warn',

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

      // 3.4 — JSDoc comments: exported/public functions and classes must have
      // JSDoc; private/local functions are exempt (publicOnly). ESLint can only
      // enforce the mechanical part of 3.4 — it cannot judge "important
      // business rules" or "name is already clear" — so this stays at 'warn'
      // even though 3.4 is REQUIRED, to keep the approximation from becoming
      // noise. Raise to 'error' if the team wants strict enforcement.
      'jsdoc/require-jsdoc': [
        'warn',
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
      'jsdoc/check-param-names': 'warn',
      'jsdoc/check-tag-names': 'warn',

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
    languageOptions: {
      parserOptions: { projectService: true },
    },
    rules: {
      // 1.1 / 1.4 / 1.6 / 1.7 / 1.8 — Naming, full version; REPLACES the
      // LAYER 1 version (type-aware entries must come first to get the
      // correct precedence)
      '@typescript-eslint/naming-convention': [
        'error',
        ...NAMING_TYPE_AWARE,
        ...NAMING_BASE,
      ],

      // 2.3 — Declare return types for exported or complex functions
      // (only flags exported/public functions; local functions may rely on inference)
      '@typescript-eslint/explicit-module-boundary-types': 'error',

      // 2.4 — Use optional chaining and nullish coalescing where appropriate
      '@typescript-eslint/prefer-optional-chain': 'error',
      '@typescript-eslint/prefer-nullish-coalescing': 'error',

      // 4.10 — no-floating-promises
      '@typescript-eslint/no-floating-promises': 'error',

      // 4.12 — Parameter object pattern: more than 3 parameters must use a
      // typed parameter object (RECOMMENDED → warn). TS-only because the
      // solution the rule requires (a typed parameter object) is TypeScript;
      // js/mjs files are usually configs/scripts, so they are not enforced.
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
      'no-undef': 'off', // TS handles this; avoid false positives
      'no-unexpected-multiline': 'off',
    },
  },

  // 2.1 — Let Prettier handle formatting rules
  // MUST BE LAST: disables every formatting rule that conflicts with Prettier
  configPrettier,
];
