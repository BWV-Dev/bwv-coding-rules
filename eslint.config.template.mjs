// ESLint flat config template (ESLint 9+)
// Copy to project root as `eslint.config.mjs`. Keep LAYER 1/2/6; enable 3/4 per stack.
// Install: npm i -D eslint @eslint/js typescript-eslint eslint-plugin-vue eslint-plugin-simple-import-sort globals
// Order matters: later layers override earlier ones (LAYER 6 must stay last).

import pluginJs from '@eslint/js';
import pluginSort from 'eslint-plugin-simple-import-sort';
import pluginVue from 'eslint-plugin-vue';
import globals from 'globals';
import tseslint from 'typescript-eslint';

/** @type {import('eslint').Linter.Config[]} */
export default [
  // LAYER 0 — Ignores (add your build dirs)
  {
    ignores: ['**/dist/**', '**/build/**', '**/coverage/**', '**/node_modules/**'],
  },

  // LAYER 1 — BASE / shared rules (always keep)
  {
    files: ['**/*.{js,mjs,cjs,ts,tsx,vue}'],
    plugins: {
      'simple-import-sort': pluginSort,
    },
    rules: {
      'simple-import-sort/imports': 'error',
      'simple-import-sort/exports': 'error',
      'no-duplicate-imports': 'error',

      // Use let/const instead of var
      'no-var': 'error',
      'prefer-const': 'error',

      // Use camelCase for variables; PascalCase for class/interface/type/enum
      // (Boolean variables must use verb prefixes — needs type info, see LAYER 3)
      '@typescript-eslint/naming-convention': [
        'error',
        {
          selector: 'variable',
          modifiers: ['destructured'],
          format: ['PascalCase', 'camelCase', 'UPPER_CASE'],
        },
        {
          selector: 'variable',
          modifiers: ['const'],
          format: ['UPPER_CASE', 'camelCase'],
        },
        { selector: 'variable', format: ['camelCase'] },
        {
          selector: ['class', 'interface', 'typeAlias', 'enum'],
          format: ['PascalCase'],
        },
      ],

      // Use single quotes
      quotes: ['error', 'single', { avoidEscape: true, allowTemplateLiterals: false }],
      // Defensive Programming (Null Checks) — partial; optional-chain in LAYER 3
      '@typescript-eslint/no-non-null-assertion': 'error',
      // No Console.log
      'no-console': 'error',
      // Enforce Semicolons
      semi: ['error', 'always'],
      // No Debugger
      'no-debugger': 'error',

      // Parameter Object Pattern
      'max-params': ['error', 5],

      // Function Complexity
      'max-lines-per-function': ['error', { max: 50, skipBlankLines: true, skipComments: true }],
      complexity: ['error', 10],
      'max-lines': ['error', 1000],
    },
  },

  // LAYER 2 — Recommended presets (always keep)
  { languageOptions: { globals: { ...globals.browser, ...globals.node } } },
  pluginJs.configs.recommended,
  ...tseslint.configs.recommended,

  // LAYER 3 — Type-aware rules (TS only; needs project tsconfig + projectService)
  {
    files: ['**/*.{ts,tsx}'],
    languageOptions: {
      parserOptions: { projectService: true },
    },
    rules: {
      // Boolean variables must use verb prefixes
      // (all selectors repeated — rule is replaced wholesale per file)
      '@typescript-eslint/naming-convention': [
        'error',
        {
          selector: 'variable',
          types: ['boolean'],
          format: ['PascalCase'],
          prefix: [
            'is', 'should', 'can', 'has', 'have', 'need', 'needs',
            'was', 'were', 'will', 'would', 'do', 'does', 'did',
          ],
        },
        { selector: 'variable', modifiers: ['const'], format: ['UPPER_CASE', 'camelCase'] },
        { selector: 'variable', format: ['camelCase'] },
        { selector: ['class', 'interface', 'typeAlias', 'enum'], format: ['PascalCase'] },
      ],
      // Defensive Programming (Null Checks)
      '@typescript-eslint/prefer-optional-chain': 'error',
    },
  },

  // LAYER 4 — Vue (delete for plain Node/TS)
  ...pluginVue.configs['flat/essential'],
  {
    files: ['**/*.vue'],
    languageOptions: {
      parserOptions: { parser: tseslint.parser, ecmaFeatures: { jsx: true } },
    },
    rules: {
      'vue/multi-word-component-names': 'off',
      'vue/one-component-per-file': 'off',
    },
  },

  // LAYER 5 — Test files (relax size + naming)
  {
    files: ['**/*.test.ts', '**/*.spec.ts', '**/__tests__/**/*.{ts,tsx,js}'],
    rules: {
      'max-lines-per-function': 'off',
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

  // LAYER 6 — Final overrides (must stay last; wins over recommended presets)
  {
    rules: {
      '@typescript-eslint/no-unused-vars': [
        'error',
        {
          argsIgnorePattern: '^_',
          varsIgnorePattern: '^_',
          caughtErrorsIgnorePattern: '^_',
        },
      ],
      // No Explicit Any
      '@typescript-eslint/no-explicit-any': 'error',
      'no-undef': 'off', // TS handles this; avoid false positives
      'no-unexpected-multiline': 'off',
    },
  },
];
