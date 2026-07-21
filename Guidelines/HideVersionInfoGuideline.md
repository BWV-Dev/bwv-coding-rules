# Hide Version Information Guideline

## Table of Contents

[**1. Purpose & Scope**](#1-purpose--scope)

[**2. What Leaks and How to Check It**](#2-what-leaks-and-how-to-check-it)

[**3. PHP Stacks**](#3-php-stacks)

- [3.1 PHP itself](#31-php-itself)
- [3.2 Laravel](#32-laravel)
- [3.3 Inertia + Vue (Vite)](#33-inertia--vue-vite)
- [3.4 CakePHP](#34-cakephp)
- [3.5 Manually-managed frontend libraries in webroot](#35-manually-managed-frontend-libraries-in-webroot)

[**4. Node.js Stacks**](#4-nodejs-stacks)

- [4.1 Backend (Node.js, Express, Nitro)](#41-backend-nodejs-express-nitro)
- [4.2 Frontend build tools (Vue, Nuxt, Vite, Webpack, esbuild)](#42-frontend-build-tools-vue-nuxt-vite-webpack-esbuild)
- [4.3 Source maps with production error tracking](#43-source-maps-with-production-error-tracking)
- [4.4 App version & environment variables in the client bundle](#44-app-version--environment-variables-in-the-client-bundle)
- [4.5 TypeScript build output](#45-typescript-build-output)
- [4.6 Package managers (npm, pnpm, yarn)](#46-package-managers-npm-pnpm-yarn)

[**5. Infrastructure (shared by both stacks)**](#5-infrastructure-shared-by-both-stacks)

[**6. AI Audit Prompt**](#6-ai-audit-prompt)

[**7. References**](#7-references)

---

## 1. Purpose & Scope

Version information (web server, runtime, framework, libraries) lets an attacker **fingerprint** the stack and target known CVEs for the exact version in use. These rules apply to **production** and internet-reachable staging, for both PHP and Node.js stacks.

> Hiding versions is defense in depth, not a substitute for patching — keep dependencies updated per the [Dependency Upgrade Guideline](./DependencyUpgradeGuideline.md), alongside the other [Web Security Rules](../WebSecurityRules.md).

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

---

## 2. What Leaks and How to Check It

This table is both the orientation and the **verification checklist** — run the checks against production (or production-like staging) after every release.

| # | Leak channel | Example | How to check | Expected |
|---|---|---|---|---|
| 1 | HTTP response headers | `Server: Apache/2.4.57 (Debian)`, `X-Powered-By: PHP/8.2.1`, `X-Powered-By: Express` | `curl -sI https://host/` | No `X-Powered-By`; `Server:` contains no version number |
| 2 | HTML source | `<meta name="generator" content="...">`, framework HTML comments | View source, or `curl -s https://host/ \| grep -i generator` | No match |
| 3 | JS/CSS banner comments | `/*! jQuery v3.6.0 ... */`, `main.js.LICENSE.txt` emitted by webpack | `grep -rE "/\*!" public/ dist/` | No versioned banners |
| 4 | Source maps | `//# sourceMappingURL=app.js.map` + the `.map` file itself (original sources, `node_modules/<lib>` paths) | `grep -r sourceMappingURL dist/`; `curl -sI https://host/<asset>.js.map` | No match; `.map` URL returns 404 |
| 5 | Version query strings | `<script src="/js/jquery.min.js?v=3.6.0">` | View source | Opaque build number / content hash only |
| 6 | Stray files in webroot | `README.md`, `CHANGELOG`, `composer.json`, `package.json`, `/.git/`, library `examples/`/`docs/` folders | `curl -sI https://host/README.md`, `/composer.json`, `/package.json`, `/.git/HEAD` | All 404 |
| 7 | Build metadata files | `stats.json`, `metafile.json`, `webpack-stats.json`, bundle-analyzer HTML, coverage reports | `curl -sI https://host/stats.json` | 404 |
| 8 | Error / debug pages | Laravel Ignition, CakePHP debug page, Express stack traces — print exact versions and file paths | Trigger a 404 and a 500 on production | No debug page, no version, no stack trace |
| 9 | Health / info endpoints | `/health`, `/status` returning app version, commit SHA, Node version | `curl -s https://host/health` | Only `{"status":"ok"}` |
| 10 | Runtime JS version constants | `app.version`, `React.version`, `axios.VERSION` | Browser devtools console | Known limit: Cannot be hidden by build config |

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

---

## 3. PHP Stacks

### 3.1 PHP itself

PHP adds `X-Powered-By: PHP/x.y.z` by default. `expose_php` is `PHP_INI_SYSTEM` — it must be set in `php.ini`, not via `ini_set()`.

**Linux** — set it in the loaded `php.ini` (e.g. `/etc/php/8.x/apache2/php.ini` or `/etc/php/8.x/fpm/php.ini`), then reload the service:

```ini
; php.ini
expose_php = Off
```

**Docker** (`php:8.x-apache` / `php:8.x-fpm`) — keep the same setting in a config file versioned with the project and copy it into the ini scan directory:

```ini
; docker/php/zz-security.ini
expose_php = Off
```

```dockerfile
# Dockerfile
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php/zz-security.ini "$PHP_INI_DIR/conf.d/"
```

### 3.2 Laravel

| Setting | Value | Why |
|---|---|---|
| `.env` `APP_DEBUG` | `false` | Debug page (Ignition) prints Laravel/PHP versions, stack traces, file paths |
| `.env` `APP_ENV` | `production` | Disables dev-only behaviors |
| Web docroot | `public/` **only** | `composer.json`, `.env`, `vendor/`, `storage/` must never be web-reachable |
| Telescope / Debugbar / Ignition | Disabled in production | Expose versions, queries and config |

### 3.3 Inertia + Vue (Vite)

```typescript
// vite.config.ts
export default defineConfig({
  build: {
    sourcemap: false,               // REQUIRED for production builds
    // if minify: 'terser': terserOptions: { format: { comments: false } }
  },
  esbuild: {
    legalComments: 'none',          // strip /*! ... */ and @license banners
  },
  define: {
    __VUE_PROD_DEVTOOLS__: 'false',
    __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: 'false',
  },
});
```

> Inertia's `X-Inertia` headers carry no version — no action needed.

### 3.4 CakePHP

| Setting | Value | Why |
|---|---|---|
| `config/app.php` → `'debug'` | `false` | Debug error page prints CakePHP/PHP versions and stack traces |
| Web docroot | `webroot/` **only** | `composer.json`, `config/`, `vendor/` must never be web-reachable |

### 3.5 Manually-managed frontend libraries in webroot

For libraries copied manually into the webroot (Bootstrap, jQuery, DataTables, ...):

1. Ship **only** the needed `dist` files — delete `README.md`, `CHANGELOG`, `LICENSE` duplicates, `examples/`, `docs/`, `src/` (all state the exact version and are requestable by URL).
2. Banner comments (`/*! jQuery v3.6.0 */`): prefer serving through the project bundler (comments stripped per 3.3/4.2); else strip in a deploy step.
3. Replace `?v=3.6.0` cache-busting query strings with an opaque build number or content hash.
4. Remove libraries no longer used — [Web Security Rules](../WebSecurityRules.md) rule 10.

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

---

## 4. Node.js Stacks

### 4.1 Backend (Node.js, Express, Nitro)

**Express**

```typescript
app.disable('x-powered-by');   // or app.use(helmet()) — removes it and adds security headers
```

- Add a custom error handler that never returns `err.stack` or internal error messages to the client. Log full details server-side only.
- `express.static` must point at a dedicated `public/` directory — never the project root, or `package.json`, `node_modules/`, `.git/` become reachable.

**Nitro (Nuxt server engine):** adds no `X-Powered-By` itself, but verify the whole chain (Node → proxy → CDN) with `curl -sI` — any hop may add headers (§5).

**Runtime environment**

- `NODE_ENV=production` for build **and** runtime — also stops Express's default error handler emitting stack traces. Never `NODE_ENV=staging`; use a separate variable (`APP_ENV`) for environment logic.
- Disable debug variables in production: `DEBUG=*`, `NODE_DEBUG=*`, custom flags — they print package/route/config names into logs.
- Never expose `process.version`, `process.versions`, `process.env`, `npm_package_version` through public APIs, logs or responses.
- Never run a dev server / dev proxy on production (HMR, inline source maps, verbose errors).

### 4.2 Frontend build tools (Vue, Nuxt, Vite, Webpack, esbuild)

> **Rule: production builds MUST have source maps disabled.** A deployed `.map` file exposes original source code and `node_modules` package paths with versions. (Need production error tracking? See 4.3.)

**Vite**

```typescript
export default defineConfig({
  build: { sourcemap: false },
  esbuild: { legalComments: 'none' }, // or 'external' — see the license note below
});
```

**Webpack**

```javascript
module.exports = {
  mode: 'production',
  devtool: false,                    // no source maps
  output: { pathinfo: false },
  optimization: {
    minimizer: [
      new TerserPlugin({
        extractComments: false,      // default true emits main.js.LICENSE.txt → version leak
        terserOptions: { format: { comments: false } },
      }),
    ],
  },
};
```

**esbuild**

```bash
# never pass --sourcemap in production scripts (default is off)
esbuild app.ts --bundle --minify --legal-comments=none --outfile=dist/app.js
```

**Nuxt**

```typescript
export default defineNuxtConfig({
  // Nuxt default is { server: true, client: false } — server maps are ON by default!
  sourcemap: { server: false, client: false },
  telemetry: false,
  debug: false,
  devtools: { enabled: false },
});
```

- Remove any `<meta name="generator">` added by modules/`useHead()`.
- The `/_nuxt/` asset prefix fingerprints Nuxt (framework, not version); change via `app.buildAssetsDir` if required.

**Vue (any bundler)**

```typescript
define: {
  __VUE_PROD_DEVTOOLS__: 'false',
  __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: 'false',
}
```

**Vue CLI (legacy)** — production source maps are on **by default**:

```javascript
// vue.config.js
module.exports = { productionSourceMap: false };
```

> **License compliance:** stripping `@license` comments does not remove the obligation. Either `legalComments: 'external'` (the generated `*.LEGAL.txt` still lists versions — do **not** deploy it), or `'none'` plus a build-time `THIRD-PARTY-NOTICES` file kept out of the webroot.

### 4.3 Source maps with production error tracking

If the project needs readable production stack traces (Sentry, Datadog, ...), do **not** simply turn source maps back on:

1. Build with hidden/external maps (no `sourceMappingURL` in the served JS): webpack `devtool: 'hidden-source-map'`; Vite `build.sourcemap: 'hidden'`; esbuild `sourcemap: 'external'` (+ `sourcesContent: false`); Nuxt `sourcemap: { client: 'hidden' }`.
2. Upload the `.map` files to the error-tracking platform (**private**).
3. **Delete `*.map` from the deploy artifact** — `hidden` only removes the URL comment; the files still exist next to the bundle.
4. Block public access to `*.map` at the web server / CDN as a safety net.

### 4.4 App version & environment variables in the client bundle

Everything embedded in public JS/CSS/HTML is readable by an attacker.

- **Do not inject the app's own version into the client**: no `import { version } from './package.json'`, no `DefinePlugin({ VERSION })`, no version string in an HTML footer or meta tag. If a release must be traceable from the client, use an **opaque build ID** mapped back to the real version privately in the observability platform.
- Bundler env mechanisms are **public by design** — every value is compiled into the client bundle: Vite `VITE_*`, Vue CLI `VUE_APP_*`, Nuxt `runtimeConfig.public`. Only put genuinely public values there — never secrets, internal endpoints or version/build metadata.

### 4.5 TypeScript build output

```jsonc
// tsconfig.build.json (production)
{
  "compilerOptions": {
    "sourceMap": false,
    "inlineSourceMap": false,
    "inlineSources": false,
    "declarationMap": false,
    "removeComments": true
  }
}
```

Do not ship `.ts` sources or `.map` files in the deploy artifact / Docker image.

### 4.6 Package managers (npm, pnpm, yarn)

- `package.json` and lockfiles list every dependency with its exact version. Bundlers never bundle them into `dist`, but keep them **outside any statically served directory**.
- Production images install runtime deps only: `npm ci --omit=dev` / `pnpm install --prod --frozen-lockfile` / `yarn install --production --frozen-lockfile` (Yarn classic; on Yarn ≥ 2 use `yarn workspaces focus --production`).
- `.dockerignore` at minimum: `.git`, `node_modules`, `*.map`, `*.md`, `docs`.

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

---

## 5. Infrastructure (shared by both stacks)

**Apache**

Keep the directives in one config file:

```apache
# security.conf — on Linux (Debian/Ubuntu): /etc/apache2/conf-available/security.conf
ServerTokens Prod
ServerSignature Off
Header always unset X-Powered-By
```

**Linux** — edit the file in place, then enable and reload:

```bash
a2enmod headers && a2enconf security && systemctl reload apache2
```

**Docker** (`php:8.x-apache`) — version the same file with the project and copy it into the image:

```dockerfile
# Dockerfile
COPY docker/apache/security.conf /etc/apache2/conf-available/security.conf
RUN a2enmod headers && a2enconf security
```

- Disable directory listing on the webroot: `Options -Indexes`.
- `ServerTokens Prod` still leaves `Server: Apache` (no version) — that is the accepted target; full removal needs ModSecurity or a fronting proxy/CDN.

**Nginx**

```nginx
server_tokens off;              # Server: nginx (version removed)
proxy_hide_header X-Powered-By; # when reverse-proxying a PHP/Node upstream
```

`Server: nginx` remains; full removal needs the `headers-more` module (`more_clear_headers Server;`).

**Health / info endpoints**

- Public `/health`, `/ping`, `/status` return only `{ "status": "ok" }` — no app version, commit SHA, runtime version or dependency info (mass-scanned by bots).
- Version/build details for ops belong on an **authenticated/internal** endpoint or in the observability platform.

**Docker**

- **Multi-stage builds**: final stage copies only `dist`/`vendor`/runtime files — sources, `.map` files and the build toolchain never reach the production image.
- Minimal, patched, regularly rebuilt base images.
- No image `LABEL`s advertising stack versions on public registries.

**CI/CD**

```bash
# fail the build if source maps leaked into the artifact
if grep -rq "sourceMappingURL" dist/; then
  echo "ERROR: source maps in production build" && exit 1
fi

# post-deploy smoke check
if curl -sI https://host/ | grep -iE "x-powered-by|server:.*[0-9]"; then
  echo "ERROR: version info in response headers" && exit 1
fi
```

- Only production-mode builds may be deployed — dev builds contain readable module paths including versions.
- Do **not** publish build reports as CI artifacts (bundle-analyzer HTML, `stats.json`, coverage HTML) — they expose the module graph and exact versions.
- Avoid printing dependency trees (`npm ls`, `pnpm why`, `nuxi info`) in CI logs with public access or long retention.

**CDN / hosting**

- Strip `X-Powered-By` and origin `Server` headers with response-header policies (CloudFront response headers policy, Cloudflare Transform Rules).
- Edge headers like `Server: cloudflare` cannot be removed and reveal only the CDN — acceptable.

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

---

## 6. AI Audit Prompt

Use this prompt with an AI coding agent (Claude Code, Cursor, Copilot, ...) that has access to the project repository to audit **source code and config** against this guideline. It is a screening step: `NG` findings must be fixed per the referenced section, and it does **not** replace the runtime verification checklist in §2 or the post-deploy smoke check in §5 — headers and URL responses can only be verified against the deployed environment.

```text
You are auditing this repository against the "Hide Version Information
Guideline" (Guidelines/HideVersionInfoGuideline.md).

1. Detect the stack first: PHP (Laravel / CakePHP / plain) or Node.js
   (Express / Nitro / Nuxt / Vite / Webpack / esbuild / Vue CLI), the web
   server (Apache / Nginx) and Docker/CI configs present in the repo.
   Only audit the guideline sections that apply to the detected stack.

2. Statically check every applicable rule. Examples:
   - php.ini / Dockerfile: expose_php = Off (§3.1)
   - .env / .env.example / config: APP_DEBUG=false, APP_ENV=production,
     CakePHP 'debug' => false for production (§3.2, §3.4)
   - Build configs: source maps disabled for production, banner/legal
     comments stripped, no version injected via define/env (§3.3, §4.2–§4.5)
   - Express: x-powered-by disabled or helmet() used; custom error handler
     that never returns stack traces (§4.1)
   - Health endpoints return only {"status":"ok"} (§5)
   - Webroot / statically served dirs: no README, CHANGELOG, composer.json,
     package.json, .git, examples/, docs/, src/, *.map; no ?v=x.y.z query
     strings; version-string replacement uses a placeholder, never an
     empty string (§3.5)
   - .dockerignore, multi-stage build, CI fails on sourceMappingURL in the
     artifact, no build reports published (§4.6, §5)
   - Apache/Nginx config in repo: ServerTokens Prod, ServerSignature Off,
     server_tokens off, X-Powered-By unset, directory listing off (§5)

3. Report the result as a table:
   | Rule (section #) | Status (OK / NG / N-A) | Evidence (file:line) | Suggested fix |
   Never mark a rule OK without concrete evidence from the repository.

4. Rules that can only be verified on the deployed environment (response
   headers, .map URLs returning 404, error/debug pages, health endpoint
   responses) must be reported as "MANUAL — verify with the Section 2
   checklist", not as OK or NG.
```

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

---

## 7. References

- [OWASP WSTG-INFO-02 — Fingerprint Web Server](https://owasp.org/www-project-web-security-testing-guide/latest/4-Web_Application_Security_Testing/01-Information_Gathering/02-Fingerprint_Web_Server)
- [OWASP WSTG-INFO-08 — Fingerprint Web Application Framework](https://owasp.org/www-project-web-security-testing-guide/latest/4-Web_Application_Security_Testing/01-Information_Gathering/08-Fingerprint_Web_Application_Framework)
- [PHP `expose_php`](https://www.php.net/manual/en/ini.core.php#ini.expose-php)
- [Apache `ServerTokens`](https://httpd.apache.org/docs/2.4/mod/core.html#servertokens) / [`ServerSignature`](https://httpd.apache.org/docs/2.4/mod/core.html#serversignature)
- [Nginx `server_tokens`](https://nginx.org/en/docs/http/ngx_http_core_module.html#server_tokens)
- [Express security best practices](https://expressjs.com/en/advanced/best-practice-security.html) / [Helmet](https://helmetjs.github.io/)
- [Vite build options](https://vite.dev/config/build-options) / [terser-webpack-plugin](https://webpack.js.org/plugins/terser-webpack-plugin/) / [esbuild `legal-comments`](https://esbuild.github.io/api/#legal-comments)

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
