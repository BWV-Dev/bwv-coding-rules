
# PHP Coding Rules & Security (BWV)
## Table of Contents
[**Common** ](#common)
<br>

[**1. Naming** ](#1-naming)
- [1.1 Use PascalCase for files, namespaces, classes, interfaces, enums and traits](#1.1)
- [1.2 Use camelCase for functions, methods, properties and variables](#1.2)
- [1.3 Use UPPER_CASE for constants](#1.3)
- [1.4 Use meaningful names and avoid unclear abbreviations](#1.4)
- [1.5 Use boolean names that describe state or capability](#1.5)

[**2. Styling & Types** ](#2-styling--types)
- [2.1 Let PHP-CS-Fixer handle formatting rules](#2.1)
- [2.2 Class layout](#2.2)
- [2.3 Prefer a maximum line length](#2.3)
- [2.4 Declare strict types and type declarations](#2.4)
- [2.5 Use constructor property promotion and readonly](#2.5)
- [2.6 Use curly braces for all flow control statements](#2.6)
- [2.7 Blank line rules inside a function](#2.7)
- [2.8 Type-Safe Comparisons](#2.8)
- [2.9 Use nullsafe and null coalescing operators](#2.9)

[**3. Comment** ](#3-comment)
- [3.1 Comments should explain why, not repeat what the code does](#3.1)
- [3.2 Single-line comments](#3.2)
- [3.3 Multi-line comments](#3.3)
- [3.4 PHPDoc comments](#3.4)
- [3.5 Use English for comments](#3.5)
- [3.6 TODO and FIXME comments](#3.6)

[**4. Usage & Code Quality** ](#4-usage--code-quality)
- [4.1 Early returns and guard clauses](#4.1)
- [4.2 Prefer built-in functions over hand-rolled nested logic](#4.2)
- [4.3 Distinguish between isset() and empty()](#4.3)
- [4.4 Named arguments](#4.4)
- [4.5 Use enums for fixed sets of values](#4.5)
- [4.6 Maximum number of lines per file](#4.6)

[**5. Security** ](#5-security)

[**6. Implement Lint** ](#6-implement-lint)

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

## Common
- Always check wiki on redmine
- Always prioritize the coding rules of the project, follow the conventions of your project
- The following coding rules have been applied in some projects, depending on the project's style, the leader will select and apply them differently
- These rules assume **PHP >= 8.2**. On an older project, apply only what its PHP version supports and keep the rest as the target when upgrading

**Reading the Priority column**

`REQUIRED` / `RECOMMENDED` say how important a rule is. The marker underneath says who catches a violation — the linter or a human:

| Marker | Meaning |
|---|---|
| *(auto-fixed)* | `composer lint` catches the whole rule; `composer lint:fix` repairs it |
| *(partly auto-fixed)* | the linter catches part of it, the rest is a code-review item |
| no marker | not lintable at all — code review only |

The exact split is listed in [Auto-fix coverage](#auto-fix-coverage).

<br>

## 1. Naming
The good way to name files, classes, functions and variables in PHP.

<table>
<tr>
<th>No</th>
<th>Rule</th>
<th>Priority</th>
<th>Example</th>
</tr>

<tr>
<td id='1.1'>

**1.1**
</td>

<td>

Use **PascalCase** (UpperCamelCase) for files, namespaces, classes, interfaces, enums, traits and enum cases. The name should be a **noun**.
</td>

<td>

**REQUIRED**
</td>

<td>

```php
// UserController.php
namespace App\Http\Controllers;

class UserController
{
    // ...
}

interface PaymentRule
{
    // ...
}

enum UserType: string
{
    case Admin = 'admin';
    case Support = 'support';
}

trait CommonTrait
{
    // ...
}
```

</td>
</tr>

<tr>
<td id='1.2'>

**1.2**
</td>

<td>

Use **camelCase** for functions, methods, properties and variables. Function and method names should start with a **verb**.
</td>

<td>

**REQUIRED**
</td>

<td>

```php
// Bad
function user_name() {}
$route_name = 'abc';

// Good 👍
function getUserName(): string {}
function redirectTo(Request $request): ?string {}

$routeName = 'abc';
```

</td>
</tr>

<tr>
<td id='1.3'>

**1.3**
</td>

<td>

Use **UPPER_CASE_UNDERSCORE** for class constants and global constants.
</td>

<td>

**REQUIRED**
</td>

<td>

```php
const TABLE_NAME = 'users';

class Invoice
{
    public const MAX_RETRY_COUNT = 3;
}
```

</td>
</tr>

<tr>
<td id='1.4'>

**1.4**
</td>

<td>

Use meaningful names. **Do not** use unclear abbreviations, single letters (except loop indexes) or data-type prefixes — the type belongs in the type declaration, not in the name (see [2.4](#2.4)).
</td>

<td>

**REQUIRED**
</td>

<td>

```php
// Bad
$fn = 'John';
$a = 20;
$strName = 'John';   // type prefix is redundant
$arrAnimals = [];

// Good 👍
$firstName = 'John';
$age = 20;
$animals = [];
```

</td>
</tr>

<tr>
<td id='1.5'>

**1.5**
</td>

<td>

Boolean names should read clearly as a predicate, state, capability or intention. Prefer `is`, `has`, `can`, `should` where they improve clarity; descriptive adjectives such as `enabled`, `visible`, `active` are acceptable.
</td>

<td>

**RECOMMENDED**
</td>

<td>

```php
$isConnected = true;
$hasPermission = true;
$canResize = false;
$shouldConfirm = true;
$enabled = true;
```

</td>
</tr>

</table>
<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br>

## 2. Styling & Types

The good way to manage formatting, typing and runtime safety in PHP projects.

<table>
<tr>
<th>No</th>
<th>Rule</th>
<th>Priority</th>
<th>Example</th>
</tr>

<tr>
<td id='2.1'>

**2.1**
</td>

<td>

Let **PHP-CS-Fixer** handle formatting rules (see [6. Implement Lint](#6-implement-lint)). Team should not manually discuss formatting in code review. The shared config takes **PSR-12** as its base and enforces:

- 4 spaces for indentation (never tabs), LF line endings
- single quotes for string literals, unless the string contains a variable or a single quote
- short array syntax `[]` instead of `array()`
- trailing comma in multi-line arrays, arguments, parameters and `match`
- one statement per line
- explicit variables in strings: `"Hello {$name}"`
- one space after the logical NOT operator: `! $isActive`
- one space around the concatenation operator: `$greeting . $name`
- `use` statements sorted alphabetically, grouped per namespace, unused imports removed
- global classes imported rather than written inline: `new \DateTimeImmutable()` becomes a `use` plus `new DateTimeImmutable()`
- an empty body collapses onto the signature line: `public function handle(): void {}`
- every PHPDoc block is multi-line, even a one-liner
- `<?php echo $x ?>` becomes `<?= $x; ?>`
- class elements ordered per [2.2](#2.2)

Two **deliberate deviations from PSR-12** — both are in the template, do not "fix" them in review:

1. The `{` of a function or method stays on the signature line (`public function issue(int $invoiceNo): Invoice {`). Classes and interfaces keep the PSR-12 form, with `{` on its own line.
2. Group imports are allowed: `use App\Models\{Invoice, User};`
</td>

<td>

**REQUIRED**<br />
*(auto-fixed)*
</td>

<td>

```php
// After `composer lint:fix`
use App\Models\{Invoice, User};
use Illuminate\Http\Request;

$animals = ['tiger', 'lion'];
$message = "Hello {$name}";

if (! $isActive) {
    return null;
}

$config = [
    'retry' => 3,
    'debug' => false,
];
```

</td>
</tr>

<tr>
<td id='2.2'>

**2.2**
</td>

<td>

**Class layout**<br />
Order the elements in a class:

- `use` trait
- Enum cases
- Constants (public → protected → private)
- Properties (public → protected → private)
- Constructor
- Destructor
- Magic methods
- PHPUnit methods (`setUp`, `tearDown`, …)
- Methods (public → protected → private)

Separate them by 1 blank line — **except enum cases**, which stay packed together. Constants and properties each get their own blank line too: PHP-CS-Fixer has no "separate the groups only" mode, so the airy form below is what `lint:fix` produces.
</td>

<td>

**REQUIRED**<br />
*(auto-fixed)*
</td>

<td>

```php
final class InvoiceService
{
    use LoggableTrait;

    public const MAX_RETRY_COUNT = 3;

    private const CACHE_KEY = 'invoice';

    public int $version = 1;

    private array $items = [];

    public function __construct(
        private readonly InvoiceRepository $repository,
    ) {}

    public function __toString(): string {
        return self::CACHE_KEY;
    }

    public function issue(int $invoiceNo): Invoice {
        // ...
    }

    protected function buildLines(array $rows): array {
        // ...
    }

    private function normalize(array $rows): array {
        // ...
    }
}
```

</td>
</tr>

<tr>
<td id='2.3'>

**2.3**
</td>

<td>

**Prefer a maximum line length of 80 characters**<br />
The limit itself is not auto-fixed — no PHP-CS-Fixer rule measures line length. When a line exceeds it, wrap it by these conventions:
- Break after a comma.
- Break before an operator. The fixer never breaks a line for you — it only moves `&&` and `||` to the start of the next line once *you* have broken there. Where to break, and every other operator, is a manual convention.

Prefer going over the limit if breaking the line would make it less readable — for example a long string literal, a URL or a fully qualified class name that should not be split.
</td>

<td>

**RECOMMENDED**<br />
*(partly auto-fixed)*
</td>

<td>

```php
if (
    ($condition1 === true || $condition2 > 0)
    && $condition3 === false // Break before an operator
    && $condition4 === 1
) {
    callSomething(
        $longNameParam, // Break after a comma
        $otherLongNameParam,
    );
}
```

</td>
</tr>

<tr>
<td id='2.4'>

**2.4**
</td>

<td>

**Declare strict types and type declarations**<br />
Add `declare(strict_types=1);` at the top of every PHP file, and declare types for parameters, return values and properties. Types belong in the signature — this is what makes [2.8](#2.8) work at runtime and removes most redundant PHPDoc (see [3.4](#3.4)).<br />
Use `void`, `?T`, union types and `never` where they describe the real contract. Use `mixed` only when the value truly can be anything.<br />
`lint:fix` inserts the missing `declare(strict_types=1);` and rewrites an implicit nullable parameter (`Customer $c = null`) into the explicit `?Customer $c = null`, but it cannot invent the type declarations — those are on you.
</td>

<td>

**REQUIRED**<br />
*(partly auto-fixed)*
</td>

<td>

```php
// Bad — no types at all
function calculateDiscount($price, $discount) {
    return $price * ($discount / 100);
}
```

```php
<?php
// Good 👍
declare(strict_types=1);

namespace App\Services;

final class PriceService
{
    private ?Customer $customer = null;

    public function calculateDiscount(
        float $price,
        float $discount,
    ): float {
        return $price * ($discount / 100);
    }

    public function findCustomer(int $customerNo): ?Customer {
        // ...
    }
}
```

</td>
</tr>

<tr>
<td id='2.5'>

**2.5**
</td>

<td>

**Use constructor property promotion and `readonly`**<br />
Promote constructor parameters instead of declaring the property and assigning it manually. Mark dependencies and value objects `readonly` when they must not change after construction.
</td>

<td>

**RECOMMENDED**
</td>

<td>

```php
// Bad
final class InvoiceService
{
    private InvoiceRepository $repository;

    public function __construct(InvoiceRepository $repository) {
        $this->repository = $repository;
    }
}

// Good 👍
final class InvoiceService
{
    public function __construct(
        private readonly InvoiceRepository $repository,
        private readonly LoggerInterface $logger,
    ) {}
}
```

</td>
</tr>

<tr>
<td id='2.6'>

**2.6**
</td>

<td>

Use curly braces for all flow control statements, even single-line bodies.
</td>

<td>

**REQUIRED**<br />
*(auto-fixed)*
</td>

<td>

```php
// Bad
if ($isTrue)
    echo 'true';
if ($arg === null) return true;

// Good 👍
if ($isTrue) {
    echo 'true';
}

if ($arg === null) {
    return true;
}
```

</td>
</tr>

<tr>
<td id='2.7'>

**2.7**
</td>

<td>

**Blank line rules inside a function**<br />
Add 1 blank line **after** each `if` block and loop block.<br />
Add 1 blank line **before** the `return` keyword.<br />
Only the `return` half is auto-fixed. The blank line after an `if` or loop block is a manual convention — the fixer works on "blank line before statement X", which cannot express "after a block" in the general case.
</td>

<td>

**RECOMMENDED**<br />
*(partly auto-fixed)*
</td>

<td>

```php
// Bad
if ($condition) {
    // ...
}
foreach ($items as $item) {
    // ...
}
return true;

// Good 👍
if ($condition) {
    // ...
}

foreach ($items as $item) {
    // ...
}

return true;
```

</td>
</tr>

<tr>
<td id='2.8'>

**2.8**
</td>

<td>

**Type-Safe Comparisons**

Use `===` instead of `==`, `!==` instead of `!=`.<br />
When comparing two values, always ensure they are of the **same data type**. Convert both sides to a common type before comparison to avoid unexpected results (e.g. `'1' === 1` is `false`).<br />
Pass `true` as the third argument of `in_array()` / `array_search()` / `array_keys()` to force strict comparison.<br />
`lint:fix` rewrites `==` / `!=` and adds the missing strict flags. Converting both sides to a common type is on you — the linter cannot know which type you meant.
</td>

<td>

**REQUIRED**<br />
*(partly auto-fixed)*
</td>

<td>

```php
// Example 1: check NULL column data from database
// Bad — when $userFlag = 0, it also returns early
$userFlag = $this->user->getUserFlag();
if ($userFlag == null) {
    return;
}

// Good 👍
$userFlag = $this->user->getUserFlag();
if ($userFlag === null) {
    return;
}

// Example 2: type mismatch (string vs number)
// Bad
$status = $request->input('status'); // returns string '1'
if ($status === 1) { ... }           // '1' === 1 → false

// Good 👍 — convert to the SAME type before comparing
if ((int) $status === 1) { ... }

// Good 👍 — strict in_array
$validStatuses = ['1', '2', '3'];
if (in_array((string) $status, $validStatuses, true)) { ... }
```

</td>
</tr>

<tr>
<td id='2.9'>

**2.9**
</td>

<td>

**Use nullsafe `?->` and null coalescing `??` / `??=`**<br />
They replace nested `null` checks and `isset()` ternaries. Note that `?->` stops the whole chain as soon as one link is `null` — do not use it to hide a value that should never be `null` (validate and fail early instead).<br />
`lint:fix` collapses `isset($x) ? $x : $y` into `??` and `$x = $x ?? $y` into `??=`. Rewriting a nested `if` chain into `?->` is manual.
</td>

<td>

**REQUIRED**<br />
*(partly auto-fixed)*
</td>

<td>

```php
// Bad
if ($user !== null) {
    $address = $user->address;

    if ($address !== null) {
        $city = $address->getCity();

        if ($city !== null) {
            $country = $city->country;
        }
    }
}

$foo = isset($bar) ? $bar : 'something';

// Good 👍
$country = $user?->address?->getCity()?->country;
$foo = $bar ?? 'something';
$options['limit'] ??= 50;
```

</td>
</tr>

</table>
<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br>

## 3. Comment

Comments are useful when they explain business context, assumptions and reasons that are not obvious from code.

<table>
<tr>
<th>No</th>
<th>Rule</th>
<th>Priority</th>
<th>Example</th>
</tr>

<tr>
<td id='3.1'>

**3.1**
</td>

<td>

Comments should explain **why**, not repeat **what** the code already says.
</td>

<td>

**REQUIRED**
</td>

<td>

```php
// Bad: repeats what the code does
// Check if user is inactive
if ($user->status === UserStatus::Inactive) {
    return;
}

// Good 👍 explains the business reason
// Inactive users are kept for audit history and must not receive notifications.
if ($user->status === UserStatus::Inactive) {
    return;
}
```

</td>
</tr>

<tr>
<td id='3.2'>

**3.2**
</td>

<td>

**Single-line comments**<br />
Use `//` (never `#`), begin with 1 whitespace, capitalize the first word and write it like a sentence.<br />
`lint:fix` converts `#` to `//` and normalises the leading space; capitalisation and wording are on you.
</td>

<td>

**RECOMMENDED**<br />
*(partly auto-fixed)*
</td>

<td>

```php
// In case no item in list, we do nothing
if (! $hasItems) {
    return false;
}
```

</td>
</tr>

<tr>
<td id='3.3'>

**3.3**
</td>

<td>

**Multi-line comments**<br />
Use them only for complex business logic, temporary migration notes or non-obvious technical constraints. All `*` must be aligned (auto-fixed); deciding *when* a block comment is warranted is a review item.
</td>

<td>

**RECOMMENDED**<br />
*(partly auto-fixed)*
</td>

<td>

```php
/*
 * This migration must keep old user numbers because external invoices
 * still reference them. Do not regenerate userNo here.
 */
$this->migrateUserContracts();
```

</td>
</tr>

<tr>
<td id='3.4'>

**3.4**
</td>

<td>

**PHPDoc comments**<br />
Write PHPDoc when it adds information the signature cannot express: a description, array shapes, `@throws`, or generics. **Do not** repeat types that are already declared in the signature ([2.4](#2.4)) — a duplicated type is one more thing that can go stale.<br />
A blank line separates the description from the tags, and each group of tags from the next — `lint:fix` inserts those for you.<br />
`lint:fix` also deletes tags that only repeat the signature, drops the whole block once nothing is left in it, and aligns what remains.
</td>

<td>

**REQUIRED**<br />
*(partly auto-fixed)*
</td>

<td>

```php
// Bad — every tag only repeats the signature
/**
 * @param Request $request
 * @return string|null
 */
public function redirectTo(Request $request): ?string {}

// Good 👍 — adds what the signature cannot say
/**
 * Builds the redirect target after login.
 * Guest users are sent back to the page they requested.
 *
 * @param array<int, string> $allowedPaths
 *
 * @throws InvalidRedirectException when the target host is not whitelisted
 */
public function redirectTo(
    Request $request,
    array $allowedPaths,
): ?string {}
```

</td>
</tr>

<tr>
<td id='3.5'>

**3.5**
</td>

<td>

**USE** English for comments.
</td>

<td>

**REQUIRED**
</td>

<td>

```php
// Bad
// Mảng chứa các sinh viên
$students = [];

// Good 👍
// Array of students
$students = [];
```

</td>
</tr>

<tr>
<td id='3.6'>

**3.6**
</td>

<td>

TODO/FIXME comments should include enough context to be actionable. If possible, include a ticket number or owner.
</td>

<td>

**RECOMMENDED**
</td>

<td>

```php
// Bad
// TODO: fix this

// Good 👍
// TODO(#123456): Remove this fallback after the partner API v2 migration.
$companyCode = $input['companyCode'] ?? $legacyCompanyCode;
```

</td>
</tr>

</table>
<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br>

## 4. Usage & Code Quality

Use tools and project conventions to keep code consistent, readable and safe.

<table>
<tr>
<th>No</th>
<th>Rule</th>
<th>Priority</th>
<th>Example</th>
</tr>

<tr>
<td id='4.1'>

**4.1**
</td>

<td>

**Early returns and guard clauses**<br />
When we have to meet certain criteria to continue execution, exit early. Flatten nesting deeper than three levels: invert the condition and return instead of wrapping the main logic in a large `else` block.<br />
`lint:fix` removes an `else` / `elseif` that follows a returning `if`, and drops a useless trailing `return`. Restructuring the logic itself is manual.
</td>

<td>

**RECOMMENDED**<br />
*(partly auto-fixed)*
</td>

<td>

```php
// Bad
public function publish(Post $post): bool {
    if ($post->isValid()) {
        if ($post->author->isActive()) {
            return $this->repository->publish($post);
        } else {
            return false;
        }
    } else {
        return false;
    }
}

// Good 👍
public function publish(Post $post): bool {
    if (! $post->isValid()) {
        return false;
    }

    if (! $post->author->isActive()) {
        return false;
    }

    return $this->repository->publish($post);
}
```

</td>
</tr>

<tr>
<td id='4.2'>

**4.2**
</td>

<td>

Avoid hand-rolled nested logic — look for a built-in function (`in_array`, `array_filter`, `array_column`, `str_contains`, …) or a `match` expression instead.
</td>

<td>

**RECOMMENDED**
</td>

<td>

```php
// Bad
if ($day) {
    if (is_string($day)) {
        $day = strtolower($day);
        if ($day === 'friday') {
            return true;
        } elseif ($day === 'saturday') {
            return true;
        } elseif ($day === 'sunday') {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

return false;

// Good 👍
if (empty($day)) {
    return false;
}

$openingDays = [
    'friday',
    'saturday',
    'sunday',
];

return in_array(strtolower($day), $openingDays, true);

// Good 👍 — match for value mapping (strict comparison by design)
$label = match ($food) {
    'apple' => 'This food is an apple',
    'cake' => 'This food is a cake',
    default => 'Unknown food',
};
```

</td>
</tr>

<tr>
<td id='4.3'>

**4.3**
</td>

<td>

Need to distinguish between `isset()` and `empty()`.

**isset()** checks whether the variable has been set — it returns `true` if the variable exists and its value is not `null`. That means `''`, `0`, `'0'`, `false` and `[]` are **set**, so `isset()` returns `true` for them.

**empty()** checks whether a variable is *empty*. These are all empty: `''`, `0`, `0.0`, `'0'`, `null`, `false`, `[]` and a declared-but-unassigned variable.

Use `isset()` when only "missing / null" matters, and `empty()` only when `0` and `''` really should be treated the same as missing — otherwise use an explicit `=== null` or `count()` check.
</td>

<td>

**REQUIRED**
</td>

<td>

```php
$data = ['quantity' => 0];

// Bad — a legit quantity of 0 is treated as "not provided"
if (empty($data['quantity'])) {
    throw new InvalidArgumentException('quantity is required');
}

// Good 👍 — only "missing / null" is rejected
if (! isset($data['quantity'])) {
    throw new InvalidArgumentException('quantity is required');
}

// Good 👍 — empty() is correct here: an empty list means nothing to do
if (empty($items)) {
    return;
}
```

</td>
</tr>

<tr>
<td id='4.4'>

**4.4**
</td>

<td>

**Named arguments**<br />
Use named arguments instead of positional ones when you want to skip default values, or when a bare `true` / `null` at the call site says nothing about its meaning.
</td>

<td>

**RECOMMENDED**
</td>

<td>

```php
// Bad
htmlspecialchars($string, ENT_QUOTES, 'UTF-8', false);

// Good 👍
htmlspecialchars($string, double_encode: false);
```

</td>
</tr>

<tr>
<td id='4.5'>

**4.5**
</td>

<td>

**Use enums for fixed sets of values**<br />
Replace magic strings/numbers and loose class constants with a backed `enum`. The type declaration then guarantees only valid values reach the function.
</td>

<td>

**RECOMMENDED**
</td>

<td>

```php
// Bad
const STATUS_ACTIVE = 1;
const STATUS_INACTIVE = 0;

public function updateStatus(int $status): void {}

// Good 👍
enum UserStatus: int
{
    case Active = 1;
    case Inactive = 0;

    public function label(): string {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
        };
    }
}

public function updateStatus(UserStatus $status): void {}

// At the boundary (request, DB), convert once
$status = UserStatus::from((int) $request->input('status'));
```

</td>
</tr>

<tr>
<td id='4.6'>

**4.6**
</td>

<td>

**Maximum number of lines per file** <br />
Limit each file to a maximum of **1000 lines** of code to enhance code quality, maintainability, and performance.

> **Exceptions** (generated code, migration files, legacy code, large service implementations) are allowed — but this is the exception, never the default. When you must exceed the limit:
> 1. Document the reason in the Pull Request description or code review comment.
> 2. Report the exception to your PM/leader before merging. An exception without a documented reason and without approval **must be rejected** in code review.
> 3. If the same file keeps exceeding the limit, raise it with the leader — revisit the architecture instead of accumulating exceptions.
</td>

<td>

**REQUIRED**
</td>

<td>

To ensure compliance with this rule, adhere to the following best practices in your code:

- Implement the Single Responsibility Principle (SRP): Ensure each file is dedicated to a single functionality or purpose.
- Modularization: Break down your code into logical modules or components that organized in separate files.
- Adhere to the Don't Repeat Yourself (DRY) principle: Use inheritance, composition, or utility functions to prevent code duplication.

</td>
</tr>

</table>

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br>

## 5. Security

See **[Web Security Rules](./WebSecurityRules.md)**.

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br>

## 6. Implement Lint

We implement PHP lint using **PHP Coding Standards Fixer**.<br />
A ready-to-use template is provided in the [`config/php/`](./config/php) folder. Each block of fixers is annotated with the rule ID it implements (e.g. `// 2.7 — Blank line rules inside a function`) so each setting can be traced back to this page; supporting formatting fixers are grouped by topic (`// Arrays`, `// Casing`, …).

| Template | Copy to project root as | Purpose |
|---|---|---|
| [config/php/.php-cs-fixer.dist.template.php](./config/php/.php-cs-fixer.dist.template.php) | `.php-cs-fixer.dist.php` | PHP-CS-Fixer config covering the auto-fixable rules of sections 2, 3 and 4.1 |

> ⚠️ **Risky fixers are ON by default.** There are exactly three — `declare_strict_types`, `strict_comparison` and `strict_param` — and between them they implement two REQUIRED rules that cannot be enforced otherwise: [2.4](#2.4) `declare(strict_types=1)` and both halves of [2.8](#2.8) (`===`, strict flags). A new project should keep them. On an existing codebase, run `composer lint` (dry-run, changes nothing) and read the diff **before** the first `composer lint:fix` — if it is too large to review safely, follow the `LEGACY OPT-OUT` block at the end of the template and move 2.4 / 2.8 to the manual review checklist.

Ref: https://github.com/PHP-CS-Fixer/PHP-CS-Fixer

#### Step 1

**Install package**

```bash
composer require --dev friendsofphp/php-cs-fixer
```

#### Step 2

**Create .php-cs-fixer.dist.php**

Copy [config/php/.php-cs-fixer.dist.template.php](./config/php/.php-cs-fixer.dist.template.php) to your project root as `.php-cs-fixer.dist.php`, then adjust the `Finder` paths to your project layout — each block is explained by its comments in the template.

Add the cache file to `.gitignore`:

```
.php-cs-fixer.cache
```

#### Step 3

**Add scripts to composer.json**

```json
"scripts": {
    "lint": "php-cs-fixer fix --dry-run --diff --verbose",
    "lint:fix": "php-cs-fixer fix --verbose"
},
```

#### Step 4

**Run composer commands**

- **composer lint** — checks and reports violations (`--diff` shows exactly what would change). Use this in CI.
- **composer lint:fix** — automatically fixes every auto-fixable rule.

`.php-cs-fixer.dist.php` in the project root is detected automatically, so no `--config` flag is needed.

**Visual Studio Code extension**

https://marketplace.visualstudio.com/items?itemName=junstyle.php-cs-fixer<br />
This extension simply provides PHP CS Fixer command (include code format).

#### Auto-fix coverage

Which rules `composer lint` catches, and which ones only a human catches. This table is the source of truth for the markers in the Priority column above.

| Rule | `composer lint` | What is left to code review |
|---|---|---|
| [1.1](#1.1) – [1.5](#1.5) Naming | ✗ | Everything. PHP-CS-Fixer does not check naming at all |
| [2.1](#2.1) Formatting | ✓ | — |
| [2.2](#2.2) Class layout | ✓ | — |
| [2.3](#2.3) Line length 80 | ~ | The length itself, and where to break. Only the position of `&&` / `||` is normalised, on lines you already broke |
| [2.4](#2.4) Strict types | ~ | The type declarations themselves; only `declare(strict_types=1)` and the `?T` on implicit nullables are added |
| [2.5](#2.5) Promotion / `readonly` | ✗ | Everything |
| [2.6](#2.6) Curly braces | ✓ | — |
| [2.7](#2.7) Blank lines | ~ | Blank line after an `if` / loop block; only the one before `return` is fixed |
| [2.8](#2.8) Type-safe comparison | ~ | Converting both sides to a common type |
| [2.9](#2.9) Nullsafe / `??` | ~ | Rewriting nested `if` chains into `?->` |
| [3.1](#3.1) Why, not what | ✗ | Everything |
| [3.2](#3.2) Single-line comments | ~ | Capitalisation and wording |
| [3.3](#3.3) Multi-line comments | ~ | Whether a block comment is warranted at all |
| [3.4](#3.4) PHPDoc | ~ | Writing the descriptions, `@throws` and array shapes — the layout around them is fixed for you |
| [3.5](#3.5) English | ✗ | Everything |
| [3.6](#3.6) TODO / FIXME | ✗ | Everything |
| [4.1](#4.1) Early returns | ~ | The restructuring; only a useless `else` / `return` is removed |
| [4.2](#4.2) – [4.5](#4.5) | ✗ | Everything |
| [4.6](#4.6) Max 1000 lines | ✗ | Everything — PHP-CS-Fixer does not measure file length |

For the ✗ rows, consider adding **PHPStan** or **PHP_CodeSniffer** to the project if you want them enforced automatically — PHP-CS-Fixer is a formatter and cannot express them.

#### Disabling a rule

PHP-CS-Fixer has no per-line disable comment, so a rule is relaxed by editing the config. Disabling is the exception, never the default:

1. **Scope it as narrowly as possible** — exclude the single path with `$finder->notPath(...)` / `->exclude(...)` instead of turning the fixer off project-wide.
2. **Always add a comment explaining why**, next to the change:

```php
// PROJECT DECISION (2026-08-26): generated API client, never edited by hand.
->notPath('src/Generated/ApiClient.php')
```

3. **Report the change to your PM/leader** before merging. A disabled rule without a written reason and without the PM being informed must be rejected in code review.
4. If the same rule keeps getting disabled across the project, raise it with the leader — revisit the rule instead of accumulating exceptions.

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
