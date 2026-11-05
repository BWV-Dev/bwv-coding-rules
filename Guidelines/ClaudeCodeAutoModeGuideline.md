# Claude Code Auto Mode Guide

This guide applies the company's standard Claude Code configuration to every employee. The configuration switches Claude Code from **manual mode** to **auto mode** but still stops for a human to approve risky operations, and blocks operations that must never happen.

Configuration file: [`config/claude/settings.template.json`](../config/claude/settings.template.json).

## Workflow Summary

1. [**Check the Claude Code version**](#1-check-the-claude-code-version).
2. [**Apply the configuration**](#2-apply-the-configuration).
3. [**Confirm the configuration is active**](#3-confirm-the-configuration-is-active).
4. [**Work in auto mode**](#4-work-in-auto-mode).

## Step-by-Step Instructions

### 1. Check the Claude Code version

Claude Code **v2.1.228 or later** is required (**v2.1.233 or later** on native Windows).

```bash
claude --version
```

Update Claude Code if your version is older.

### 2. Apply the configuration

- Open your user settings file (create it if it does not exist):
  - **macOS / Linux:** `~/.claude/settings.json`
  - **Windows:** `C:\Users\<username>\.claude\settings.json`
- Merge the keys from [`settings.template.json`](../config/claude/settings.template.json) into the existing file. **Do not overwrite the whole file**: keep your existing settings and add the `permissions`, `autoMode`, and `sandbox` keys. If a key already exists, merge the arrays (`ask`, `deny`, ...) instead of replacing them.

### 3. Confirm the configuration is active

Check in the Claude Code CLI: `/permissions` is not available in Claude Desktop. The settings file is shared, so the result also applies to Claude Desktop.

- Open a terminal and run `claude`. The mode indicator below the input box shows **auto mode**.
- Run `/permissions`:
  - The **Ask** tab lists rules such as `Bash(git push:*)` and `WebFetch`.
  - The **Deny** tab lists rules such as `Read(./.env)` and `Bash(printenv*)`.
  - If these rules are missing, the file was not merged correctly; check the JSON syntax and the file path.

### 4. Work in auto mode

When Claude asks for approval (`ask`), before approving, read the exact command or file and check:

- **Git push / `gh pr create`**: the target branch and remote are correct, and the changes are the ones you intend to publish.
- **Installing or removing dependencies**: the package name is spelled correctly and is the package you expect.
- **Network access (`WebFetch`, `curl`, ...)**: the URL is a trusted domain and no secret or personal data is sent.
- **Editing CI/CD, Docker, or Claude configuration files**: review the diff; these files affect the whole team or the security of Claude itself.

If you are not sure, reject and ask Claude to explain first.

## What This Configuration Does

**`permissions`**
- Every new session starts in auto mode.
- Disallows the skip-all-checks mode (`--dangerously-skip-permissions`).

**`permissions.ask`** — pause and ask a human to approve
- Git: `git push`, `git push --force`, `git reset --hard`, `gh pr create`.
- Deleting files: `rm` and PowerShell delete commands (`Remove-Item`, `del`, `rd`, ...).
- Installing/removing dependencies: `npm install/remove/uninstall`, `pnpm add/remove`, `yarn add/remove`.
- Fetching from the network: `WebFetch`, `curl`, `wget`, `Invoke-WebRequest`, `Invoke-RestMethod`.
- Editing sensitive files: CI/CD (`.github/workflows`, `deploy*.yml`), `Dockerfile`, `docker-compose`, Claude's own configuration.

**`permissions.deny`** — hard block, cannot be approved
- Reading secret files: `.env*`, `*.key`, `*.pem`, `secrets/`, `credentials/`, `~/.ssh`, `~/.aws`, `~/.azure`.
- Force-pushing to or deleting `main` / `develop`; deleting tags.
- `aws iam`.
- Commands that expose secrets: `infisical export/secrets`, printing environment variables (`printenv`, `env`, `Get-ChildItem Env:`), reading `.env` via PowerShell (`Get-Content`, `type`, `Select-String`, `findstr`).

**`sandbox`**
- OS-level isolation for shell commands: blocks reading `~/.ssh` and `~/.aws/credentials`; network limited to GitHub and the npm/yarn registries; `docker` runs outside the sandbox.
- Not supported on native Windows.
