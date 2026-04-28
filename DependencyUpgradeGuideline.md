# Dependency Upgrade Guideline

## Table of Contents

[**1. Purpose & When to Upgrade**](#1-purpose--when-to-upgrade)

[**2. Semantic Versioning Overview**](#2-semantic-versioning-overview)

- [2.1 Version Format](#21-version-format)
- [2.2 Major Zero (0.y.z) - Special Attention](#22-major-zero-0yz---special-attention)

[**3. Execution Workflow**](#3-execution-workflow)

- [Step 1: Library Usage Analysis](#step-1-library-usage-analysis)
- [Step 2: Version Selection & Maturity Check](#step-2-version-selection--maturity-check)
- [Step 3: Breaking Change Investigation & Risk Assessment](#step-3-breaking-change-investigation--risk-assessment)
- [Step 4: Report & PM Approval](#step-4-report--pm-approval)
- [Step 5: Implementation](#step-5-implementation)
- [Step 6: Testing & Verification](#step-6-testing--verification)
- [Step 7: Completion Report](#step-7-completion-report)

[**4. Reporting Guidelines**](#4-reporting-guidelines)

- [4.1 Roles & Responsibilities](#41-roles--responsibilities)
- [4.2 Upgrade Report Template](#42-upgrade-report-template)
- [4.3 Completion Report Template](#43-completion-report-template)

[**5. Appendix**](#5-appendix)

- [5.1 Useful Tools & Resources](#51-useful-tools--resources)

---

## 1. Purpose & When to Upgrade

This guideline standardizes dependency updates and refactoring across projects. It ensures consistent procedures for:

- **Risk assessment** - evaluating impact before upgrading.
- **Version selection** - choosing versions based on clear criteria.
- **Execution workflow** - a step-by-step process from investigation to testing.
- **Reporting** - knowing when and how to report to DEV Leader and PM.

> **Background:** Teams have handled upgrades inconsistently. This guideline eliminates ambiguity and serves as a single source of truth.

### Required Triggers

Dependency upgrades **MUST** be performed when any of the following conditions is met:

| # | Trigger | Example |
|---|---|---|
| 1 | **Client request** | Client explicitly requests upgrading a framework or library (including infrastructure upgrades such as PHP 8.1 → 8.5). |
| 2 | **Security patch** | A known vulnerability (CVE) is published for the current version. |
| 3 | **Required functionality** | A new feature needed by the project is only available in a newer version. |
| 4 | **End of Life (EOL)** | The current version is no longer maintained or receiving security patches. |

### Prohibited Triggers

Do **NOT** upgrade solely for:
- "Keeping up" with the latest version without a concrete need.
- Developer curiosity or preference.
- Unmeasured minor performance gains.

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

---

## 2. Semantic Versioning Overview

Reference: [semver.org](https://semver.org/) | [semver.npmjs.com](https://semver.npmjs.com/)

### 2.1 Version Format

Version numbers follow **MAJOR.MINOR.PATCH** (`X.Y.Z`):

| Component | Incremented When | Impact |
|---|---|---|
| **MAJOR** (`X`.y.z) | Incompatible API changes (breaking changes) | ⛔ High - may require code modifications |
| **MINOR** (x.`Y`.z) | New functionality added in a backward-compatible manner | ⚠️ Medium - generally safe, but should verify |
| **PATCH** (x.y.`Z`) | Backward-compatible bug fixes | ✅ Low - typically safe to apply |

### 2.2 Major Zero (0.y.z) - Special Attention

> ⚠️ **Major version zero (`0.y.z`) is for initial development. The public API SHOULD NOT be considered stable. Anything MAY change at any time.**

When using a library with major version `0`:
- Treat **every MINOR version bump** (`0.Y.z`) as potentially a **breaking change**.
- Do **NOT** assume backward compatibility between `0.1.x` and `0.2.x`.
- Only use `0.y.z` libraries when there is **no stable alternative** (version `≥ 1.0.0`).
- Must report this risk explicitly to Project Manager before adopting.

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

---

## 3. Execution Workflow

### Workflow Overview

```
┌──────────────────────────────────────────────────────────┐
│                   TRIGGER DETECTED                       │
│  (Client request / Security CVE / Feature need / EOL)    │
└──────────────────────────┬───────────────────────────────┘
                           │
                           ▼
┌──────────────────────────────────────────────────────────┐
│           Step 1: Library Usage Analysis                 │
│  - Search codebase for all usage of the library          │
│  - Document functions, classes, methods used             │
└──────────────────────────┬───────────────────────────────┘
                           │
                           ▼
┌──────────────────────────────────────────────────────────┐
│       Step 2: Version Selection & Maturity Check         │
│  - Apply mandatory & flexible criteria                   │
│  - Check version maturity (wait period)                  │
└──────────────────────────┬───────────────────────────────┘
                           │
                           ▼
┌──────────────────────────────────────────────────────────┐
│  Step 3: Breaking Change Investigation & Risk Assessment │
│  - Read CHANGELOG and migration guide                    │
│  - Only check breaking changes for USED functions        │
│  - Determine risk level (🟢 🟡 🟠 🔴)                  │
└──────────────────────────┬───────────────────────────────┘
                           │
                           ▼
┌──────────────────────────────────────────────────────────┐
│          Step 4: Report & PM Approval                    │
│  - Prepare report (use template)                         │
│  - DEV Member → DEV Leader → PM                          │
│  - All risk levels: PM approval required                 │
└──────────────────────────┬───────────────────────────────┘
                           │ (Approved ✅)
                           ▼
┌──────────────────────────────────────────────────────────┐
│             Step 5: Implementation                       │
│  - Create dedicated branch                               │
│  - Update dependency version                             │
│  - Fix breaking changes                                  │
└──────────────────────────┬───────────────────────────────┘
                           │
                           ▼
┌──────────────────────────────────────────────────────────┐
│          Step 6: Testing & Verification                  │
│  - Monkey testing / Performance testing                  │
│  - Automated unit tests (if project has them)            │
└──────────────────────────┬───────────────────────────────┘
                           │
                           ▼
┌──────────────────────────────────────────────────────────┐
│            Step 7: Completion Report                     │
│  - Document results, performance metrics                 │
│  - DEV Member → DEV Leader → PM                          │
└──────────────────────────────────────────────────────────┘
```

### Step 1: Library Usage Analysis

1. Search codebase for library references.
2. List used functions, classes, methods, and constants.
3. Document in a table; fill last three columns in Step 3.

| # | Function / Class / Method | File(s) Where Used | Description | Breaking Change | Required Action | Estimated Time |
|---|---|---|---|---|---|---|
| 1 | `LibraryClass::methodA()` | `src/Service/UserService.php` | Used for user authentication | _(Step 3)_ | _(Step 3)_ | _(Step 3)_ |
| 2 | `helperFunction()` | `src/Controller/ApiController.php` | Used for request validation | _(Step 3)_ | _(Step 3)_ | _(Step 3)_ |
| ... | ... | ... | ... | ... | ... | ... |

### Step 2: Version Selection & Maturity Check

**Mandatory Criteria** — the selected version MUST satisfy all:

| # | Criterion | Description |
|---|---|---|
| 1 | **Environment compatibility** | Must support the target environment (e.g., if upgrading to PHP 8.5, the library must support PHP 8.5). |
| 2 | **No known vulnerabilities** | No unpatched CVEs. |
| 3 | **Version maturity** | Must pass the maturity check below. |

**Flexible Criteria** — apply based on scenario:

| Scenario | Priority |
|---|---|
| **Full project upgrade** | Latest stable version |
| **Fix incompatibility** | Fewest breaking changes |
| **Urgent timeline** with many breaking changes | Minimum compatible version with fewest breaking changes |
| **Security patch** | Latest patch within the same MINOR version |

**Version Maturity** — do NOT blindly adopt the newest release:

| Release Type | Minimum Wait Period | Exception |
|---|---|---|
| **PATCH** (`x.y.Z`) | ✅ Immediate. | - |
| **MINOR** (`x.Y.0`) | ⏳ 2–4 weeks, or until `x.Y.1+`. | Urgently needed feature, or backed by reputable team (React, Laravel, CakePHP…). |
| **MAJOR** (`X.0.0`) | ⏳ 1–3 months, or until `X.1.0+`. | EOL, explicit client requirement, or forced by environment upgrade. |
| **Major zero** (`0.y.z`) | ⚠️ No guaranteed stability. | Only when no stable alternative exists; document the risk. |

Also check: weekly downloads (NPM/Packagist), open bugs on GitHub, maintainer credibility, changelog quality.

> If the wait period is skipped, document the justification in the report.

### Step 3: Breaking Change Investigation & Risk Assessment

> ⚠️ **Scope:** Investigate only used functions and shared modules. Ignore unused functions.

1. Read CHANGELOG and migration guide (if available).
2. Filter for changes affecting used items and shared components.
3. Update table from Step 1 with breaking changes, actions, and time.
4. Check shared concerns: transitive dependencies, config formats, CLI, env requirements. Add rows if needed.
5. Determine risk level:

| Risk Level | Condition |
|---|---|
| 🟢 **Low** | No breaking changes in used functions or common modules. |
| 🟡 **Medium** | Breaking changes in used functions, limited scope, straightforward fix. |
| 🟠 **High** | Breaking changes affect multiple/shared modules, OR library is major zero (`0.y.z`). |
| 🔴 **Critical** | Core framework/infrastructure upgrade, multiple interdependent libraries, OR significant fix time. |

> **Note:** Version type (MAJOR/MINOR/PATCH) is an indicator, not a determinant. Evaluate by actual project impact.

### Step 4: Report & PM Approval

1. Create Redmine ticket; prepare report using the [Upgrade Report Template](#42-upgrade-report-template).
2. DEV Member → DEV Leader → PM.
3. Wait for PM approval before proceeding.

> ⚠️ Do not start changes without approval. See [Roles & Responsibilities](#41-roles--responsibilities).

### Step 5: Implementation

1. Create branch.
2. Update version in package file (`package.json`, `composer.json`, etc.).
3. Run install/update.
4. Fix breaking changes.
5. Run linting and static analysis.
6. Commit.

### Step 6: Testing & Verification

> ⚠️ Test regardless of whether breaking changes were found.

#### 6.1 Monkey Testing

Write test cases for all features using the upgraded library; compare old vs new behavior.

> Use the [Monkey Test Template (Google Sheets)](https://docs.google.com/spreadsheets/d/1VouHff39pjwo9aklpgJFADetmj7wPavMpxoLid-r4Go/edit). Attach the link in the Completion Report.

#### 6.2 Performance Testing

| Metric | Before | After |
|---|---|---|
| Page load time | ... ms | ... ms |
| API response time | ... ms | ... ms |
| Feature processing time | ... ms | ... ms |

#### 6.3 Automated Unit Tests (if available)

Run all existing tests; fix failures.

### Step 7: Completion Report

Fill in the [Completion Report Template](#43-completion-report-template) and submit: DEV Member → DEV Leader → PM.

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

---

## 4. Reporting Guidelines

### 4.1 Roles & Responsibilities

| Role | Responsibility |
|---|---|
| **DEV Member** | Investigation (Steps 1–3), implementation (Step 5), testing (Step 6), report writing. |
| **DEV Leader** | Reviews report, supervises upgrade, escalates to PM. |
| **Project Manager (PM)** | Approves all upgrades, communicates with client, makes go/no-go decision. |

#### Approval Flow by Risk Level

| Risk Level | Approval Flow |
|---|---|
| 🟢 **Low** / 🟡 **Medium** | DEV Leader reviews → PM approves. |
| 🟠 **High** / 🔴 **Critical** | DEV Leader reviews → PM approves plan and timeline → PM communicates with client. |

> Report unexpected issues immediately to DEV Leader and PM.

### 4.2 Upgrade Report Template

> **Dependency Upgrade Report**
>
> **1. Overview**
> - **Library:** `[Library Name]`
> - **Current Version:** `[x.y.z]`
> - **Target Version:** `[x.y.z]`
> - **Upgrade Type:** `[MAJOR / MINOR / PATCH]`
> - **Risk Level:** `[Low / Medium / High / Critical]`
>
> **2. Reason for Upgrade**
>
> [Security patch / client request / new feature requirement / EOL.]
>
> **3. Version Selection**
>
> [Why this version was chosen; mandatory criteria, flexible criteria, maturity check; justify if wait period was skipped.]
>
> **4. Breaking Changes**
>
> [Attach the completed table from Steps 1 & 3.]
>
> **5. Estimated Timeline**
>
> | Task | Estimated Time |
> |---|---|
> | Investigation & Report | ... hours |
> | Implementation | ... hours |
> | Testing | ... hours |
> | **Total** | **... hours** |

### 4.3 Completion Report Template

> **Dependency Upgrade Completion Report**
>
> - **Library:** `[Library Name]`
> - **Upgraded From:** `[x.y.z]` → **To:** `[x.y.z]`
>
> **Monkey Test Results:** [Link to TestResult Sheet]
>
> **Performance Test Results:** [Attach table from Step 6.2.]
>
> **Automated Unit Test Results:** [Attach evidence, if available.]
>
> **Issues Encountered:** [Unexpected issues and how they were resolved.]

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

---

## 5. Appendix

### 5.1 Useful Tools & Resources

| Tool | Language / Ecosystem | Purpose |
|---|---|---|
| `npm outdated` | Node.js | List outdated packages |
| `npm audit` | Node.js | Check for known vulnerabilities |
| `composer outdated` | PHP | List outdated packages |
| `composer audit` | PHP | Check for known vulnerabilities |
| `pip list --outdated` | Python | List outdated packages |
| `pip audit` | Python | Check for known vulnerabilities |
| `flutter pub outdated` | Flutter/Dart | List outdated packages |
| [semver.npmjs.com](https://semver.npmjs.com/) | - | SemVer range calculator |
| [semver.org](https://semver.org/) | - | SemVer specification |

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
