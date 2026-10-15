# dev — Laragon + Infisical launcher

Runs the projects in Laragon on Apache + `mod_php` (like production), with secrets
pulled from [Infisical](https://infisical.com) instead of `.env` files.

- **No secrets on disk.** Secret values are kept only in the memory of the Apache
  processes. Generated config files contain variable names (`${...}`), never values.
- **Several PHP versions at the same time.** Each PHP version runs in its own
  Apache process on its own loopback IP; every project keeps its `*.test` domain.
- **Reuses Laragon.** Domains, document roots, Apache modules, SSL certificate and
  PHP builds all come from your Laragon installation. You only declare what Laragon
  does not know.
- **No application changes.** Secrets reach PHP through `getenv()` and `$_SERVER`,
  which is what Laravel's `env()` and CakePHP's `env()` read.

## Requirements

- Windows 10/11, PowerShell 7 (`pwsh`) or Windows PowerShell 5.1
- Laragon with Apache and Thread Safe x64 PHP builds (`php*apache2_4.dll` present)
- [Infisical CLI](https://infisical.com/docs/cli/overview) 0.43.145 or later (needed for `.infisical.yaml`), logged in (`infisical login`)
- `curl.exe` (ships with Windows 10+)

## Quick start

1. Put this folder at `<laragon>\usr\dev\` (the script finds Laragon from its own location).
2. In Laragon, **stop Apache** and uncheck it in *Preferences > Services & Ports*
   so *Start All* does not grab port 80. Keep using Laragon for MySQL etc.
3. In each project that should use Infisical:
   ```powershell
   cd D:\laragon\www\my-project
   infisical init        # creates .infisical.yaml
   ```
4. Start everything:
   ```powershell
   D:\laragon\usr\dev\dev.cmd up
   ```
   Add `D:\laragon\usr\dev` to `PATH` to type just `dev up`.

On the first run Windows may show a UAC prompt to update the `hosts` file (see [hosts](#hosts-file)).

## Commands

| Command | Description |
|---|---|
| `dev up [--no-hosts]` | Stop old instances, generate config, fetch secrets, start Apache, update `hosts` |
| `dev restart` | Same as `dev up`. Run it after changing secrets in Infisical |
| `dev down` | Stop all instances |
| `dev status` | Show instances, PIDs and health |
| `dev run <project> <cmd...>` | Run a CLI command with the project's PHP version and secrets (via `infisical run`) |
| `dev selftest [php...]` | Start each PHP build with fake secrets; check that they reach PHP and that the web loads the same extensions as the CLI |
| `dev hosts-reset` | Remove the `hosts` entries managed by `dev` and restore Laragon's lines |

Examples:

```powershell
dev up
dev run ai-hub php artisan migrate
dev run bwv-tools composer install
dev selftest 8.2 7.2
```

## Configuration

### What is read from Laragon

| Source | Used for |
|---|---|
| `usr\laragon.ini` | www folders, Apache build, default PHP, HTTP/HTTPS ports, SSL on/off |
| `etc\apache2\sites-enabled\auto.*.conf` | domain and DocumentRoot of each project (Laragon's own vhosts; Laragon 6 and later) |
| `usr\tpl\VirtualHost.tpl` | vhost for www folders Laragon has not generated a vhost for yet |
| `bin\apache\<build>\conf\httpd.conf` | Apache modules and settings |
| `etc\apache2\httpd-ssl.conf`, `etc\ssl\laragon.crt` | SSL settings and certificate |
| `bin\php\*` | available PHP builds (Thread Safe x64 only) |

Hand-written vhosts in `sites-enabled` that don't follow Laragon's template are included
as-is into the default PHP instance, after the project vhosts, so they cannot shadow a project's domain.

### What is read from each project

`.infisical.yaml` (created by `infisical init`), or the legacy `.infisical.json`. If either exists,
the project's secrets are fetched from that Infisical project. The environment defaults to whatever
the Infisical CLI picks: the `git-branch-to-environment` mapping for the current branch,
`default-environment`, or `dev`.

### `projects.json`

Only for what Laragon does not know. Every key is optional. Copy
`projects.example.json` to `projects.json` to start:

```json
{
  "projects": {
    "bwv-tools": { "php": "7.2" },
    "ai-hub":    { "php": "8.2", "env": "dev", "path": "/" },
    "legacy":    { "projectId": "<infisical-project-id>" },
    "abc":       { "infisical": false }
  },
  "apache": { "8.2": "httpd-2.4.54-win64-VS16" },
  "hostnameTemplate": "{name}.test",
  "infisicalPath": "C:\\path\\to\\infisical.exe"
}
```

| Key | Meaning |
|---|---|
| `projects.<folder>.php` | PHP version for the project: `"7.2"`, `"8.2.13"` or a full folder name. Default: the PHP selected in Laragon |
| `projects.<folder>.env` / `.path` | Infisical environment / secret folder (overrides `.infisical.yaml`) |
| `projects.<folder>.projectId` | Use Infisical for a project without `.infisical.yaml` |
| `projects.<folder>.infisical` | `false` disables Infisical for the project |
| `apache.<php version>` | Pin an Apache build for a PHP version. Normally not needed |
| `hostnameTemplate` | Domain for folders without a Laragon vhost. Default `{name}.test` |
| `infisicalPath` | Path to the Infisical CLI if it is not on `PATH` |

Quote PHP versions as strings (`"8.10"`, not `8.10`).

## How it works

```
infisical export ──stdout (pipe)──► dev.ps1 memory ──process env──► httpd ──► PHP ($_SERVER, getenv)
```

1. `dev up` discovers projects from Laragon and groups them by PHP version.
2. For projects with Infisical, `infisical export --format json` runs in the project folder
   (up to 8 in parallel). Its output is read from a pipe into memory.
3. For each PHP version it writes an Apache config under `runtime\instances\<php>\`:
   Laragon's `httpd.conf` minus Laragon's `Listen`/vhosts/`mod_php`, plus this instance's
   `Listen`, `LoadModule php`, and the project vhosts. Each vhost gets lines such as
   ```apache
   SetEnv DB_PASSWORD "${IDEV_AI_HUB__DB_PASSWORD}"
   ```
   Laragon copies its default PHP's DLLs (`php8ts.dll`, `libsqlite3.dll`, …) into Apache's `bin\`, and
   Windows loads DLLs from the `httpd.exe` folder first. An instance on another PHP version therefore runs
   `httpd.exe` from `runtime\instances\<php>\bin\`: hard links to Apache's `bin\` where those DLLs are
   replaced by the instance PHP's own.
4. The secret values are set as environment variables (`IDEV_<PROJECT>__<KEY>`) only while
   `httpd.exe` is being started, then removed from the launcher's environment. Apache
   replaces `${...}` with the values in memory when it reads its config.
5. A health check (`/__dev/health`) confirms each instance serves PHP.

### IP addresses

| Instance | IP |
|---|---|
| Laragon's default PHP | `127.0.0.1` (Laragon's existing `hosts` entries keep working) |
| Any other PHP version | `127.<major><minor>.<patch>.1`, e.g. PHP 7.2.34 → `127.72.34.1` |

All instances use Laragon's ports (80/443).

### hosts file

`dev up` makes every domain point to the IP of the instance serving it:

- Laragon lines that already match are left alone.
- Laragon lines pointing to the wrong IP are commented out with `#dev-off# `.
- Missing entries are added in a block at the top of the file, between
  `# >>> laragon-dev (dev.ps1) >>>` and `# <<< laragon-dev (dev.ps1) <<<`.

Editing `hosts` needs admin rights, so a UAC prompt appears only when something actually
changes. A backup is saved to `runtime\hosts.bak`. `dev hosts-reset` undoes all changes.
Use `--no-hosts` to skip this step.

### SSL

HTTPS uses Laragon's `laragon.crt`, which Laragon adds to the Windows trust store.
`dev up` warns about domains the certificate does not cover; those still work over HTTP.
To let Laragon regenerate the certificate: `dev down` → start Apache in Laragon once →
stop it → `dev up`.

## Where secrets are (and are not) stored

Not on disk:

- `runtime\instances\*\sites\*.conf` contain only `${IDEV_...}` names.
- `runtime\state.json` contains only project names, IPs and PIDs.

In memory:

- the `httpd.exe` processes, from `dev up` until `dev down`;
- briefly, the `dev.ps1` process during `dev up`;
- the command started by `dev run`, while it runs.

Outside the launcher's control:

- The Infisical CLI keeps an **encrypted** offline backup in `%USERPROFILE%\.infisical\secrets-backup\`.
- Windows may page process memory to `pagefile.sys`; crash dumps of `httpd` contain its environment.
- Other processes running as the same Windows user can read `httpd`'s environment.
- `phpinfo()`, framework debug pages (`APP_DEBUG=true`) and code that logs `$_SERVER`
  expose secrets exactly as they would with `.env`.

## Limitations

- **Restart to apply changes.** A running process cannot change its environment, so
  `dev restart` re-fetches the secrets of every project.
- **Projects on the same PHP version share one Apache process.** A project can read
  another project's `IDEV_*` variables through `getenv()` if they run on the same PHP
  version. Projects on different PHP versions are fully separated.
- **Multi-line values are skipped** (Apache `SetEnv` is single-line). Store keys such as
  PEM files base64-encoded and decode them in the application.
- **CLI commands are not covered by `dev up`.** Run them through `dev run <project> ...`.
  Avoid caching config with secrets (e.g. `php artisan config:cache`).

## Troubleshooting

| Symptom | Cause / fix |
|---|---|
| `Port already in use` | Laragon's Apache (or another server) is listening on 80/443. Stop it in Laragon |
| A PHP version fails the health check, or an extension works in `dev run` but not on the web | Run `dev selftest <php>`. It reports PHP/Apache pairs that cannot start and extensions the web does not load |
| `infisical export failed` | Run `infisical login`; check `.infisical.yaml`, `env`, `projectId` |
| `skipped <KEY> (invalid variable name)` | The Infisical key is not a valid environment variable name |
| A site shows Laragon's www index | The domain resolves to the wrong instance. Run `dev up` without `--no-hosts` and check `hosts` |
| Logs | `runtime\instances\<php>\logs\error.log` and `access.log` |

Run `dev selftest` after adding or upgrading PHP or Apache builds.

## Files

```
dev.ps1                 launcher
dev.cmd                 wrapper so `dev <command>` works from cmd/PowerShell
projects.json           local configuration (not committed)
projects.example.json   configuration example
runtime\                generated config, Apache bin\ links, logs, PIDs, state, hosts backup (safe to delete when stopped)
```
