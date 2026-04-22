# Dependency Upgrade Guideline

## Table of Contents

[**1. Purpose**](#1-purpose)

[**2. Semantic Versioning Overview**](#2-semantic-versioning-overview)

- [2.1 Version Format](#21-version-format)
- [2.2 Major Zero (0.y.z) - Special Attention](#22-major-zero-0yz---special-attention)

[**3. When to Upgrade**](#3-when-to-upgrade)

- [3.1 Required Triggers](#31-required-triggers)
- [3.2 Prohibited Triggers](#32-prohibited-triggers)

[**4. Execution Workflow**](#4-execution-workflow)

- [Step 1: Library Usage Analysis](#step-1-library-usage-analysis)
- [Step 2: Version Selection & Maturity Check](#step-2-version-selection--maturity-check)
- [Step 3: Breaking Change Investigation & Risk Assessment](#step-3-breaking-change-investigation--risk-assessment)
- [Step 4: Report & PM Approval](#step-4-report--pm-approval)
- [Step 5: Implementation](#step-5-implementation)
- [Step 6: Testing & Verification](#step-6-testing--verification)
- [Step 7: Completion Report](#step-7-completion-report)

[**5. Risk Assessment**](#5-risk-assessment)

- [5.1 Risk Level Classification](#51-risk-level-classification)

[**6. Version Selection Criteria**](#6-version-selection-criteria)

- [6.1 Mandatory Criteria](#61-mandatory-criteria)
- [6.2 Flexible Criteria](#62-flexible-criteria)
- [6.3 Version Maturity Assessment](#63-version-maturity-assessment)

[**7. Reporting Guidelines**](#7-reporting-guidelines)

- [7.1 Roles & Responsibilities](#71-roles--responsibilities)
- [7.2 Upgrade Report Template](#72-upgrade-report-template)
- [7.3 Completion Report Template](#73-completion-report-template)

[**8. Appendix**](#8-appendix)

- [8.1 Useful Tools & Resources](#81-useful-tools--resources)

---

## 1. Purpose

This guideline standardizes the process for dependency updates and refactoring across all projects. It ensures a consistent procedure for:

- **Risk assessment** - evaluating the impact before upgrading.
- **Version selection** - choosing the right version based on clear criteria.
- **Execution workflow** - a step-by-step process from investigation to testing.
- **Reporting** - knowing when and how to escalate to DEV Leader and PM.

> **Background:** Different teams have been handling dependency upgrades inconsistently across projects (NodeJS projects, PHP projects, etc.). This guideline eliminates ambiguity and provides a single source of truth for all members involved in the upgrade process.

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

---

## 2. Semantic Versioning Overview

Reference: [semver.org](https://semver.org/) | [semver.npmjs.com](https://semver.npmjs.com/)

### 2.1 Version Format

A version number follows the format **MAJOR.MINOR.PATCH** (`X.Y.Z`):

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

## 3. When to Upgrade

### 3.1 Required Triggers

Dependency upgrades **MUST** be performed when any of the following conditions is met:

| # | Trigger | Example |
|---|---|---|
| 1 | **Client request** | Client explicitly requests upgrading a framework or library (including infrastructure upgrades such as PHP 8.1 → 8.5). |
| 2 | **Security patch** | A known vulnerability (CVE) is published for the current version. |
| 3 | **Required functionality** | A new feature needed by the project is only available in a newer version. |
| 4 | **End of Life (EOL)** | The current version is no longer maintained or receiving security patches. |

### 3.2 Prohibited Triggers

Do **NOT** upgrade dependencies solely for:

- "Keeping up" with the latest version without a concrete need.
- Developer curiosity or preference.
- Minor performance improvements that have not been measured or requested.

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

---

## 4. Execution Workflow

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

**Objective:** Identify all functions and features of the library that the project currently uses.

**Actions:**
1. Search the codebase for all import/require/use statements referencing the library.
2. List every function, class, method, and constant from the library that is used.
3. Document the list in a table (columns 1–4). The last three columns will be filled during [Step 3](#step-3-breaking-change-investigation--risk-assessment).

| # | Function / Class / Method | File(s) Where Used | Description | Breaking Change | Required Action | Estimated Time |
|---|---|---|---|---|---|---|
| 1 | `LibraryClass::methodA()` | `src/Service/UserService.php` | Used for user authentication | _(Step 3)_ | _(Step 3)_ | _(Step 3)_ |
| 2 | `helperFunction()` | `src/Controller/ApiController.php` | Used for request validation | _(Step 3)_ | _(Step 3)_ | _(Step 3)_ |
| ... | ... | ... | ... | ... | ... | ... |

### Step 2: Version Selection & Maturity Check

**Objective:** Select the appropriate target version based on the project's needs and verify its maturity.

**Actions:**
1. Apply the [Mandatory Criteria](#61-mandatory-criteria) to filter candidate versions.
2. Apply the [Flexible Criteria](#62-flexible-criteria) based on the upgrade scenario.
3. Verify the selected version passes the [Version Maturity Assessment](#63-version-maturity-assessment).
4. If the maturity wait period is skipped due to urgency, document the justification in the report.

### Step 3: Breaking Change Investigation & Risk Assessment

**Objective:** Determine if any function used by the project is affected by breaking changes in the selected target version (from Step 2), and assess the risk level.

> ⚠️ **Scope:** Only investigate breaking changes for **functions that are actually used in the project** (from the usage list in Step 1) and **common/shared modules** (e.g., configuration, base classes, middleware). Do NOT spend time investigating breaking changes for functions that the project does not use.

**Actions:**
1. Read the library's **CHANGELOG** from the current version to the target version (NPM, Packagist, GitHub Releases).
2. Read the library's **migration guide** (if available).
3. **Filter** the changelog: identify only the breaking changes that match entries in the usage list from Step 1, plus any changes to common/shared components (configuration format, environment requirements, etc.).
4. **Update the table from Step 1**: for each affected function, fill in the **Breaking Change**, **Required Action** and **Estimated Time** columns. Only list items relevant to the project.
5. **Also check** common/shared concerns:
   - Transitive dependency changes (dependencies of the library itself).
   - Changes to configuration file formats.
   - Changes to CLI commands (if applicable).
   - Changes to minimum environment requirements (PHP/Node.js/Python version, etc.).

   If any of the above are found, add a new row to the table in Step 1 for each common/shared concern.

6. **Determine the risk level** based on the findings above. See [Risk Level Classification](#51-risk-level-classification).

### Step 4: Report & PM Approval

**Objective:** Report findings to DEV Leader and PM. Obtain approval before proceeding.

**Actions:**
1. DEV Member create a new Redmine ticket with tracker Research/ConfirmingDesign. Prepares the report using the [Upgrade Report Template](#72-upgrade-report-template).
2. Submit to DEV Leader for technical review.
3. DEV Leader reviews and submits to PM.
4. PM reviews, communicates with the client if necessary, and approves/rejects.
5. **Wait for approval before proceeding to Step 5.**

> ⚠️ **Do NOT start code changes before receiving the required approval.** See [Roles & Responsibilities](#71-roles--responsibilities) for the approval flow by risk level.

### Step 5: Implementation

**Objective:** Perform the actual upgrade and fix any breaking changes.

**Actions:**
1. Create a dedicated branch (naming convention depends on each project).
   ```
   Example: feature/upgrade-{ticket-number}
   ```
2. Update the dependency version in the package manager file (`package.json`, `composer.json`, etc.).
3. Run the package manager install/update command.
4. Fix all breaking changes identified in Step 3.
5. Run linting and static analysis tools.
6. Commit with a clear message (format depends on each project).
   ```
   Example: chore: upgrade {library-name} from {old-version} to {new-version}
   ```

### Step 6: Testing & Verification

**Objective:** Ensure the upgrade does not break existing functionality and does not degrade performance.

> ⚠️ Testing must be performed **regardless of whether there are breaking changes or not.** Breaking changes may be undocumented or missing from the changelog and migration guide.

**Actions:**

#### 6.1 Monkey Testing

Write test cases for all features that use the upgraded library (refer to the usage list from Step 1). Test cases should compare the behavior between the old version and the new version. Execute the test cases and verify that all functionality works identically.

> Use the [Monkey Test Template (Google Sheets)](https://docs.google.com/spreadsheets/d/1VouHff39pjwo9aklpgJFADetmj7wPavMpxoLid-r4Go/edit) to document test cases and results. Attach the link in the [Completion Report](#73-completion-report-template).

#### 6.2 Performance Testing

Compare performance metrics before and after the upgrade. Record the results in the table below. Attach the completed table in the [Completion Report](#73-completion-report-template).

| Metric | Before | After |
|---|---|---|
| Page load time | ... ms | ... ms |
| API response time | ... ms | ... ms |
| Feature processing time | ... ms | ... ms |

#### 6.3 Automated Unit Tests (if the project has them)

Run all existing automated unit tests. Fix any failures.

### Step 7: Completion Report

**Objective:** Document the upgrade results for internal tracking and PM review.

**Actions:**
1. Fill in the [Completion Report Template](#73-completion-report-template) with test results and performance metrics.
2. Submit to DEV Leader for review.
3. DEV Leader reviews and submits to PM.

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

---

## 5. Risk Assessment

### 5.1 Risk Level Classification

Before proceeding with any upgrade, assess the risk level based on the **actual impact** on the project, not solely on the version type (MAJOR/MINOR/PATCH).

> All risk levels require **PM approval** before proceeding. The risk level determines the **depth of analysis** required in the report.

| Risk Level | Condition |
|---|---|
| 🟢 **Low** | No breaking changes in functions or common modules used by the project. |
| 🟡 **Medium** | Breaking changes exist in used functions, but scope is limited (few files affected) and the fix is straightforward. |
| 🟠 **High** | Breaking changes affect multiple modules or common/shared code, OR the library is a major zero (`0.y.z`) library. |
| 🔴 **Critical** | Upgrade involves core framework/infrastructure (e.g., CakePHP, Laravel, React), OR requires upgrading multiple interdependent libraries simultaneously, OR estimated fix time is significant. |

> **Note:** The version type (MAJOR/MINOR/PATCH) is an **indicator**, not a determinant. A MAJOR update with no breaking changes in used functions can be 🟢 Low, while a MINOR update with breaking changes in common modules can be 🟠 High. Always evaluate based on actual project impact.

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

---

## 6. Version Selection Criteria

### 6.1 Mandatory Criteria

The selected version **MUST** satisfy all of the following:

| # | Criterion | Description |
|---|---|---|
| 1 | **Environment compatibility** | Must support the target environment and infrastructure (e.g., upgrading from PHP 8.1 → 8.5 means the library must support PHP 8.5). |
| 2 | **No known security vulnerabilities** | The version must not have any unpatched CVEs. |
| 3 | **Version maturity** | Must pass the [Version Maturity Assessment](#63-version-maturity-assessment). |

### 6.2 Flexible Criteria

| # | Criterion | Description |
|---|---|---|
| 1 | **Latest version** | Prefer the latest stable version when possible. |
| 2 | **Fewest breaking changes** | Prefer the version with the least breaking changes affecting the project. |

> **Note:** Flexible criteria are situational. The priority depends on the scenario (see below).

#### Version Selection by Scenario

| Scenario | Priority | Rationale |
|---|---|---|
| **Full project upgrade** (e.g., upgrading entire project infrastructure) | Latest version | Minimizes future upgrade debt. The team has allocated time for comprehensive changes. |
| **Fix incompatibility** (e.g., a specific library conflict) | Fewest breaking changes | Minimizes scope and risk. Goal is to fix the issue, not to overhaul. |
| **Urgent timeline** with many breaking changes in the latest version | Minimum compatible version with fewest breaking changes | Time constraints do not allow extensive investigation or code changes. |
| **Security patch** | Latest patch within the same MINOR version | Addresses the vulnerability while minimizing code changes. |

### 6.3 Version Maturity Assessment

> Do **NOT** blindly adopt the newest release. Assess version maturity based on the release type:

| Release Type | Minimum Wait Period | Exception |
|---|---|---|
| **PATCH** (`x.y.Z`) | ✅ Can be adopted immediately. | - |
| **MINOR** (`x.Y.0`) | ⏳ Wait **2–4 weeks**, or until the first patch release (`x.Y.1+`) is published. | Urgently needed new feature, or backed by a reputable team (e.g., React, Laravel, CakePHP). |
| **MAJOR** (`X.0.0`) | ⏳ Wait **1–3 months**, or until the first minor release (`X.1.0+`) is published. | EOL of current version, explicit client requirement, or forced by environment upgrade. |
| **Major zero** (`0.y.z`) | ⚠️ No guaranteed stability at any time. | Only when no stable alternative exists; must document the risk. |

**Additional maturity indicators to check:**

| Indicator | What to Look For |
|---|---|
| **Community adoption** | Weekly downloads on NPM/Packagist - has the community already adopted this version? |
| **Issue tracker** | Number of bugs/regressions reported for this specific version on GitHub. |
| **Maintainer credibility** | Is the library maintained by a company/team or a solo individual? |
| **Changelog quality** | Does the library provide detailed, well-documented changelogs and migration guides? |

> **If the wait period is skipped** due to urgency, this must be documented in the report with the justification.

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

---

## 7. Reporting Guidelines

### 7.1 Roles & Responsibilities

| Role | Responsibility |
|---|---|
| **DEV Member** | Performs the investigation (Step 1–3), implementation (Step 5), testing (Step 6), and writes the report. |
| **DEV Leader** | Reviews the risk assessment and report, supervises the upgrade process, escalates to PM. |
| **Project Manager (PM)** | Approves all upgrades (all risk levels), communicates impact and timeline to the client, makes the final go/no-go decision. |

#### Approval Flow by Risk Level

| Risk Level | Approval Flow |
|---|---|
| 🟢 **Low** / 🟡 **Medium** | DEV Leader reviews → PM approves. |
| 🟠 **High** / 🔴 **Critical** | DEV Leader reviews → PM approves plan and timeline → PM communicates with client. |

> If an unexpected issue is discovered during the upgrade that may affect timeline or functionality, report **immediately** to DEV Leader and PM.

### 7.2 Upgrade Report Template

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
> [Describe why this upgrade is necessary: security patch, client request, new feature requirement, EOL, etc.]. See [When to Upgrade](#3-when-to-upgrade).
>
> **3. Version Selection Criteria**
>
> [Why this version was chosen, include required criteria, flexible criteria, and maturity check (justify if the wait period was skipped).] See [Version Selection Criteria](#6-version-selection-criteria).
>
> **4. Breaking Changes**
> 
> [Attach the completed [Breaking Change Investigation table from Step 1 & Step 3](#step-1-library-usage-analysis).]
>
> **5. Estimated Timeline**
>
> | Task | Estimated Time |
> |---|---|
> | Investigation & Report | ... hours |
> | Implementation (fixing breaking changes) | ... hours |
> | Testing | ... hours |
> | **Total** | **... hours** |

### 7.3 Completion Report Template

> **Dependency Upgrade Completion Report**
>
> - **Library:** `[Library Name]`
> - **Upgraded From:** `[x.y.z]` → **To:** `[x.y.z]`
>
> **Monkey Test Results**
>
> [Link to TestResult Sheet]
>
> **Performance Test Results**
>
> [Attach the completed [Performance Testing table from Step 6.2](#62-performance-testing).]
>
> **Automated Unit Test Results (if available)**
>
> [Attach evidence (e.g., test runner output, coverage report).]
>
> **Issues Encountered**
>
> [Any unexpected issues and how they were resolved.]

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

---

## 8. Appendix

### 8.1 Useful Tools & Resources

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
