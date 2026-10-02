
# PHP Coding Rules & Security (BWV)
## Table of Contents
[**Common** ](#common)
<br>

[**1. Naming** ](#1-naming)
- [1.1 Use PascalCase for class files, namespaces, classes, interfaces, enums, enum cases and traits](#1.1)
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
- [4.3 Do not use empty(); use isset() or explicit checks](#4.3)
- [4.4 Named arguments](#4.4)
- [4.5 Use enums for fixed sets of values](#4.5)
- [4.6 Maximum number of lines per file](#4.6)
- [4.7 Pin PHP version](#4.7)

[**5. Security** ](#5-security)

[**6. Implement Lint** ](#6-implement-lint)
- [Laravel setup](#laravel-setup)
- [CakePHP setup](#cakephp-setup)
- [Shared steps](#shared-steps)
- [Rector](#rector)
- [PHP-CS-Fixer](#php-cs-fixer)
- [PHPStan](#phpstan)
- [Running all three](#running-all-three)
- [Disabling a rule](#disabling-a-rule)

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

## Common
- Always check wiki on redmine
- Always prioritize the coding rules of the project, follow the conventions of your project
- The following coding rules have been applied in some projects, depending on the project's style, the leader will select and apply them differently
- These rules assume **PHP 8.5**
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

Use **PascalCase** (UpperCamelCase) for class files, namespaces, classes, interfaces, enums, traits and enum cases. The name should be a **noun**.
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
    public const int MAX_RETRY_COUNT = 3;
}
```

</td>
</tr>

<tr>
<td id='1.4'>

**1.4**
</td>

<td>

Use meaningful names. **Do not** use unclear abbreviations, single letters (except loop indexes) or data-type prefixes.
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

Let **PHP-CS-Fixer** format the code (see [6. Implement Lint](#6-implement-lint)). Do not discuss what it enforces in code review — only what it cannot: line length ([2.3](#2.3)) and the blank line after a block ([2.7](#2.7)). The config is **PSR-12** plus:

- 4 spaces for indentation, LF line endings
- single quotes, unless the string contains a variable or a single quote; variables in braces: `"Hello {$name}"`
- short array syntax `[]`; trailing comma in multi-line arrays, arguments, parameters and `match`
- one statement per line
- one space after `!` and around `.`: `! $isActive`, `$greeting . $name`
- `use` sorted and grouped per namespace (`use App\Models\{Invoice, User};` — differs from PSR-12), global classes imported, unused imports removed
- no parentheses around `new`: `new Money($amount)->add($tax)`
- `{` of a function or method on the signature line (differs from PSR-12), of a class, interface or enum on its own line; an empty body collapses: `public function handle(): void {}`
- PHPDoc of constants, properties and methods is always multi-line
- `<?php echo $x ?>` becomes `<?= $x; ?>` (view templates are excluded)
- class elements ordered per [2.2](#2.2)
</td>

<td>

**REQUIRED**
</td>

<td>

```php
// After `composer lint:fix`
use App\Models\{Invoice, User};
use Illuminate\Http\Request;

$animals = ['tiger', 'lion'];
$message = "Hello {$name}";
$total = new Money($amount)->add($tax);

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
</td>

<td>

**REQUIRED**
</td>

<td>

```php
final class InvoiceService implements Stringable
{
    use LoggableTrait;

    public const int MAX_RETRY_COUNT = 3;

    private const string CACHE_KEY = 'invoice';

    public int $version = 1;

    private int $retryCount = 0;

    public function __construct(
        private readonly InvoiceRepository $repository,
    ) {}

    public function __toString(): string {
        return self::CACHE_KEY;
    }

    public function issue(int $invoiceNo): Invoice {
        // ...
    }

    protected function buildLines(Invoice $invoice): InvoiceLines {
        // ...
    }

    private function normalize(InvoiceLines $lines): InvoiceLines {
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
Wrap a longer line after a comma and before an operator. Do not split a long string, URL or fully qualified class name just to fit.
</td>

<td>

**RECOMMENDED**
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
Add `declare(strict_types=1);` to every PHP file except view templates, and type every parameter, return value, property and class constant.<br />
Narrow `mixed` values (request input, config, JSON, database rows) once, at the boundary, with `is_*()`, `instanceof` or a typed accessor (`$request->integer()` in Laravel, `toInt()` in CakePHP).<br />
PHPStan reports a missing type but not how a `mixed` value is used — that part is checked in code review.
</td>

<td>

**REQUIRED**
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
Promote constructor parameters instead of assigning them by hand. Mark a property `readonly` when it must not change after construction, and the class `readonly` when all its properties are. Rector applies all three.
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
final readonly class InvoiceService
{
    public function __construct(
        private InvoiceRepository $repository,
        private LoggerInterface $logger,
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

**REQUIRED**
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
Add 1 blank line **after** each `if` or loop block, unless it is the last statement of its block.<br />
Add 1 blank line **before** `return`, unless it is the first statement of its block (e.g. a guard clause).
</td>

<td>

**RECOMMENDED**
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

**Type-Safe Comparisons**<br />
Use `===` / `!==`, and pass `true` as the strict flag of `in_array()`, `array_search()` and `array_keys()`.<br />
Both sides must have the **same type** (`'1' === 1` is `false`): convert once, at the boundary ([2.4](#2.4)), and cast only after `is_numeric()` — `(int) 'abc'` silently becomes `0`.<br />
PHPStan reports every loose comparison. When Rector turns `switch` into `match`, check the types: `match` compares strictly.
</td>

<td>

**REQUIRED**
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
// Bad (Laravel)
$status = $request->input('status'); // returns string '1'
if ($status === 1) { ... }           // '1' === 1 → false

// Good 👍 (Laravel) — convert to the SAME type once, at the boundary
$status = $request->integer('status');
if ($status === 1) { ... }

// Good 👍 (CakePHP) — toInt() returns null, not 0, for a value
// that is not an integer ('abc', '1.5', '')
use function Cake\Core\toInt;

$status = toInt($this->request->getData('status'));
if ($status === 1) { ... }

// Good 👍 — strict in_array
$validStatuses = [1, 2, 3];
if (in_array($status, $validStatuses, true)) { ... }
```

</td>
</tr>

<tr>
<td id='2.9'>

**2.9**
</td>

<td>

**Use nullsafe `?->` and null coalescing `??` / `??=`**<br />
Use them instead of nested `null` checks and `isset()` ternaries, but not on a value that must never be `null` — fail early instead.<br />
PHPStan reports member access on a possibly-`null` value.
</td>

<td>

**REQUIRED**
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
Use `//` (never `#`), begin with 1 whitespace, capitalize the first word and write it like a sentence.
</td>

<td>

**RECOMMENDED**
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
Use them only for complex business logic, temporary migration notes or non-obvious technical constraints.
</td>

<td>

**RECOMMENDED**
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
Write PHPDoc only for what the signature cannot say: a description, `@throws`, and the element type of an `array`, `iterable` or generic (`@param list<string> $paths`) — PHPStan reports it when missing. **Do not** repeat a declared type.<br />
Separate the description and each group of tags with a blank line.
</td>

<td>

**REQUIRED**
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
When we have to meet certain criteria to continue execution, exit early. Flatten nested conditions: invert the condition and return instead of wrapping the main logic in a large `else` block.
</td>

<td>

**RECOMMENDED**
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
if (! is_string($day)) {
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

**Do not use `empty()`** — it treats `0`, `'0'`, `''`, `false` and `[]` as missing, so a valid `0` is rejected. PHPStan reports every call.<br />
Use `isset()` when only missing / `null` matters (it is `true` for `0`, `''` and `[]`); otherwise check exactly what you mean: `=== null`, `=== ''`, `=== []`, `=== 0` or `count($items) === 0`.
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

// Good 👍 — an empty list means nothing to do: say so explicitly
if ($items === []) {
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
htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, 'UTF-8', false);

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

    public function getLabel(): string {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
        };
    }
}

public function updateStatus(UserStatus $status): void {}

// At the boundary (request, DB), validate and convert once
// (Laravel)
$request->validate(['status' => ['required', Rule::enum(UserStatus::class)]]);
$status = $request->enum('status', UserStatus::class)
    ?? throw new InvalidArgumentException('Invalid status');
```

```php
// (CakePHP) src/Model/Table/UsersTable.php
use Cake\Database\Type\EnumType;

public function initialize(array $config): void {
    parent::initialize($config);

    // $user->status is a UserStatus from here on
    $this->getSchema()->setColumnType('status', EnumType::from(UserStatus::class));
}

public function validationDefault(Validator $validator): Validator {
    // EnumType silently turns an invalid value into null —
    // this rule is what rejects it
    return $validator->enum('status', UserStatus::class);
}
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

<tr>
<td id='4.7'>

**4.7**
</td>

<td>

**Pin PHP version** <br />
Every PHP project must declare an exact PHP version and keep it consistent across `composer.json`, CI configuration, and Docker/runtime configuration.
- `composer.json` config
  - `require.php` declares the supported version constraint.
  - `config.platform.php` locks Composer's dependency resolution to an exact version, regardless of the PHP binary actually installed.
- Keep the CI workflow and Dockerfile base image pinned to the same exact version.
</td>

<td>

**REQUIRED**
</td>

<td>

```json
// composer.json
{
    "require": {
        "php": "^8.5"
    },
    "config": {
        "platform": {
            "php": "8.5.11"
        }
    }
}
```

```dockerfile
# Good 👍 Docker base image aligned with composer.json platform
FROM php:8.5.11-fpm
```

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

We implement PHP lint using **Rector**, **PHP Coding Standards Fixer** and **PHPStan**:

- **Rector** rewrites code: constructor promotion, `readonly`, `switch` → `match`, removing an `else` after a `return`, type declarations, and PHP 8.5 syntax and deprecations (e.g. `new Foo()->bar()`, `array_find()` / `array_any()`, `#[\Override]`). Guard clauses ([4.1](#4.1)) and `?->` ([2.9](#2.9)) stay manual. The Laravel template adds the rector-laravel sets; CakePHP has no maintained Rector set for code quality, so its template uses the generic sets only.
- **PHP-CS-Fixer** formats code ([2.1](#2.1)). Some of its rewrites overlap with Rector (`??=`, a useless `else`, PHPDoc tags that repeat the signature, parentheses around `new`): the result is the same, but a behaviour you turn off must be turned off in both ([Disabling a rule](#disabling-a-rule)).
- **PHPStan** only reports, at **level 8** with `phpstan-strict-rules` and the framework extension (Larastan for Laravel, `cakedc/cakephp-phpstan` for CakePHP). What it reports is noted in [2.4](#2.4), [2.8](#2.8), [2.9](#2.9), [3.4](#3.4) and [4.3](#4.3), plus non-boolean conditions. It does not check operations on `mixed` ([2.4](#2.4)).

| Laravel | CakePHP | Copy to project root as |
|---|---|---|
| [laravel/rector.template.php](./config/php/laravel/rector.template.php) | [cakephp/rector.template.php](./config/php/cakephp/rector.template.php) | `rector.php` |
| [laravel/.php-cs-fixer.dist.template.php](./config/php/laravel/.php-cs-fixer.dist.template.php) | [cakephp/.php-cs-fixer.dist.template.php](./config/php/cakephp/.php-cs-fixer.dist.template.php) | `.php-cs-fixer.dist.php` |
| [laravel/phpstan.dist.template.neon](./config/php/laravel/phpstan.dist.template.neon) | [cakephp/phpstan.dist.template.neon](./config/php/cakephp/phpstan.dist.template.neon) | `phpstan.dist.neon` |

Set up a project with the steps for its framework, then the [Shared steps](#shared-steps).

### Laravel setup

For Laravel 13.

1. **Pin the PHP version** as in [4.7](#4.7).

2. **Install packages**

   ```bash
   composer require --dev rector/rector driftingly/rector-laravel friendsofphp/php-cs-fixer phpstan/phpstan phpstan/phpstan-strict-rules larastan/larastan
   ```

3. **Remove the tools that conflict with the templates**

   - Run `composer remove --dev laravel/pint`.
   - Delete any `phpstan.neon` / `phpstan.neon.dist`: PHPStan reads them before `phpstan.dist.neon`, so they would override the template.

4. **Copy the templates** from the Laravel column of the table above, then adjust `withPaths()` (Rector), the `Finder` (PHP-CS-Fixer) and `paths` / `excludePaths` (PHPStan) to your project layout.

5. Continue with the [Shared steps](#shared-steps).

### CakePHP setup

For CakePHP 5.4.

1. **Pin the PHP version** as in [4.7](#4.7).

2. **Install packages**

   ```bash
   composer require --dev rector/rector friendsofphp/php-cs-fixer phpstan/phpstan phpstan/phpstan-strict-rules cakedc/cakephp-phpstan dereuromark/cakephp-ide-helper
   bin/cake plugin load IdeHelper --only-cli --optional
   ```

   - `cakedc/cakephp-phpstan` enables its own rules: no `debug()` / `dd()` / `pr()` calls, no array access on entities, and checks on associations, behaviors, components, mailers and controller actions. To turn one off, set it to `false` under `parameters.cakeDC` (e.g. `disallowEntityArrayAccessRule: false`), following [Disabling a rule](#disabling-a-rule).

3. **Remove the skeleton's own tooling**

   - Delete `phpstan.neon`: PHPStan reads it before `phpstan.dist.neon`, so it would override the template.
   - Delete `psalm.xml` and `phpcs.xml`, and run `composer remove --dev cakephp/cakephp-codesniffer`.
   - Remove the `check`, `cs-check` and `cs-fix` scripts from `composer.json`: `check` would clash with the one in the [Shared steps](#shared-steps). Keep `test`.
   - Delete the `.github/` folder. It belongs to the cakephp/app repository itself (issue templates, Dependabot), and its `ci.yml` runs phpcs. Write your own CI with `composer check` and `composer test`.

4. **Copy the templates** from the CakePHP column of the table above. They cover `config/`, `plugins/`, `src/` and `tests/`; if the project has no `plugins/` folder, remove it from all three.

5. **Configure IdeHelper** — add this block to `config/app.php`:

   ```php
   'IdeHelper' => [
       'arrayAsGenerics' => true,
       'objectAsGenerics' => true,
       'genericsInParam' => 'detailed',
       'concreteEntitiesInParam' => 'strict',
       'tableBehaviors' => true,
       'propertyTypeMap' => [
           'actsAs' => 'array<string, mixed>',
           'helpers' => 'array<int|string, string|array<string, mixed>>',
           'components' => 'array<int|string, string|array<string, mixed>>',
           'paginate' => 'array<string, mixed>',
       ],
   ],
   ```

6. **Do the [Shared steps](#shared-steps)**, then add an `annotate` script — one line for the app, plus one per local plugin:

   ```json
   "annotate": [
       "@php bin/cake.php annotate all",
       "@php bin/cake.php annotate all -p Admin"
   ]
   ```

### Shared steps

1. **Ignore the caches** — add to `.gitignore`:

   ```
   .rector/
   .php-cs-fixer.cache
   .phpstan/
   ```

2. **Add scripts to composer.json**

   ```json
   "scripts": {
       "rector": "rector process --dry-run",
       "rector:fix": "rector process",
       "lint": "php-cs-fixer fix --dry-run --diff --verbose",
       "lint:fix": "php-cs-fixer fix --verbose",
       "stan": "phpstan analyse --memory-limit=1G",
       "stan:baseline": "phpstan analyse --memory-limit=1G --generate-baseline",
       "fix": ["@rector:fix", "@lint:fix"],
       "check": ["@rector", "@lint", "@stan"]
   },
   ```

3. **First run**
   - Run `composer fix`, then the test suite: Rector and the risky fixers rewrite code, and the tests are what show a change in behaviour.
   - If `composer rector` still reports changes, run `composer fix` again — some rewrites only become possible after another one (a closure turned into an arrow function gets its return type on the next pass).
   - Run `composer stan`. On a new project, fix what it still reports in the skeleton's own code (a handful of errors on Laravel, around 20 on CakePHP) instead of baselining it. On an existing codebase, follow [Existing codebase](#existing-codebase).

### Rector

- **composer rector** — dry-run, reports what Rector would rewrite, changes nothing.
- **composer rector:fix** — applies Rector's rewrites.

### PHP-CS-Fixer

- **composer lint** — checks and reports violations (`--diff` shows exactly what would change).
- **composer lint:fix** — automatically fixes every rule it can.

> ⚠️ `declare_strict_types` is a *risky* fixer: on an existing codebase it turns silent type juggling into a `TypeError` at runtime. Run `composer lint` and read the diff before the first `composer lint:fix`. If the diff is too large to review safely, turn the fixer off at the start of the project, following [Disabling a rule](#disabling-a-rule).

#### VSCode extension

https://marketplace.visualstudio.com/items?itemName=junstyle.php-cs-fixer<br />
This extension simply provides PHP CS Fixer command (include code format).

### PHPStan

- **composer stan** — analyses the codebase and reports violations.
- **composer stan:baseline** — (re)generates `phpstan-baseline.neon`, freezing the current errors so `composer stan` only fails on new ones (see [Existing codebase](#existing-codebase)).

#### Existing codebase

Every project runs **level 8** — do not lower or raise it. On an existing codebase:

1. Run `composer rector:fix` — the type declarations it adds remove many missing-type errors.
2. Run `composer stan:baseline` and include the baseline in `phpstan.dist.neon`:

   ```neon
   includes:
       - phpstan-baseline.neon
   ```

3. Shrink the baseline as you touch that code. Regenerate it only to shrink it, never to absorb errors in new code.

#### VSCode extension *(optional)*

https://marketplace.visualstudio.com/items?itemName=SanderRonde.phpstan-vscode<br />
Shows PHPStan errors inline as you type, without waiting for `composer stan`.

### Running all three

- **composer fix** — applies Rector's rewrites, then formats the result.
- **composer check** — dry-run of all three tools, changes nothing. Use this in CI, together with the test suite.

### Disabling a rule

Disabling a rule is an exception, with the same steps in all three tools:

1. **Scope it as narrowly as possible**, never project-wide:
   - **PHP-CS-Fixer** — exclude the path: `$finder->notPath(...)`.
   - **Rector** — skip the rule for that path: `->withSkip([RuleClass::class => [__DIR__ . '/path/to/File.php']])`.
   - **PHPStan** — fix the type first; otherwise `// @phpstan-ignore <identifier> (<reason>)` on the line. `@phpstan-ignore-line` / `@phpstan-ignore-next-line` are rejected.
   - A behaviour both Rector and PHP-CS-Fixer implement is turned off in both — e.g. to keep an `else` after a `return`, skip `RemoveAlwaysElseRector` **and** turn off `no_useless_else` / `no_superfluous_elseif`.

2. **Always add a comment explaining why**, next to the change:

   - PHP-CS-Fixer:
     ```php
     // PROJECT DECISION (2026-08-26): generated API client, never edited by hand.
     ->notPath('app/Generated/ApiClient.php')
     ```
   - Rector:
     ```php
     ->withSkip([
         // PROJECT DECISION (2026-08-26): generated API client, never edited by hand.
         ReadOnlyPropertyRector::class => [__DIR__ . '/app/Generated/ApiClient.php'],
     ])
     ```
   - PHPStan — the reason lives inline in the ignore comment itself, no separate comment needed:
     ```php
     // @phpstan-ignore argument.type (acme/billing-sdk v2 declares string, but the API takes the int id; fixed upstream in v3 — #123456.)
     $client->fetchInvoice($invoiceNo);
     ```

3. **Report the change to your PM/leader** before merging — a disabled rule without a written reason must be rejected in code review. If the same rule keeps getting disabled, raise it with the leader and revisit the rule.

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
