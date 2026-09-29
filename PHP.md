
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
- These rules assume **PHP >= 8.5**. On an older project, apply only what its PHP version supports and keep the rest as the target when upgrading
- The lint templates in [6. Implement Lint](#6-implement-lint) come in two sets: **Laravel 12+** (13 for a new project) and **CakePHP 5.3+** — the first versions that run on PHP 8.5. Examples use Laravel APIs unless a `(CakePHP)` variant is shown
- `REQUIRED` / `RECOMMENDED` say how important a rule is in code review. Whatever part of a rule the tools in [6. Implement Lint](#6-implement-lint) enforce is mandatory in CI regardless of that label. A leader who does not want to apply such a rule turns it off at the start of the project, following [Disabling a rule](#disabling-a-rule). The PHPStan level is not one of those rules: every project runs level 8 (see [Existing codebase](#existing-codebase))
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

Use **PascalCase** (UpperCamelCase) for class files, namespaces, classes, interfaces, enums, traits and enum cases. The name should be a **noun**.<br />
A class file is named after the class it holds (PSR-4). Other files — config, routes, migrations, views, tool configs such as `rector.php` — follow the framework's own convention.
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

Use **UPPER_CASE_UNDERSCORE** for class constants and global constants. Enum cases are the exception — they use PascalCase (see [1.1](#1.1)).
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

Let **PHP-CS-Fixer** handle formatting rules (see [6. Implement Lint](#6-implement-lint)). Team should not manually discuss the formatting the fixer enforces in code review — only what it cannot enforce (line length in [2.3](#2.3), the blank line after a block in [2.7](#2.7)). The shared config takes **PSR-12** as its base and enforces:

- 4 spaces for indentation (never tabs), LF line endings
- single quotes for string literals, unless the string contains a variable or a single quote
- short array syntax `[]` instead of `array()`
- trailing comma in multi-line arrays, arguments, parameters and `match`
- one statement per line
- explicit variables in strings: `"Hello {$name}"`
- one space after the logical NOT operator: `! $isActive`
- one space around the concatenation operator: `$greeting . $name`
- `use` statements sorted alphabetically, grouped per namespace (`use App\Models\{Invoice, User};` — a deliberate deviation from PSR-12), unused imports removed
- global classes imported rather than written inline: `new \DateTimeImmutable()` becomes a `use` plus `new DateTimeImmutable()`
- no parentheses around `new` when calling a member on it: `new Money($amount)->add($tax)` instead of `(new Money($amount))->add($tax)`
- the `{` of a function or method stays on the signature line (a deliberate deviation from PSR-12); classes, interfaces and enums keep `{` on its own line
- an empty body collapses onto the signature line: `public function handle(): void {}`
- every PHPDoc block of a class constant, property or method is multi-line, even a one-liner
- `<?php echo $x ?>` becomes `<?= $x; ?>` in the files the fixer runs on — view templates (Blade, CakePHP `templates/`) are excluded (see [6. Implement Lint](#6-implement-lint))
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
When a line exceeds it, wrap it by these conventions:
- Break after a comma.
- Break before an operator.

Prefer going over the limit if breaking the line would make it less readable — for example a long string literal, a URL or a fully qualified class name that should not be split.
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
Add `declare(strict_types=1);` at the top of every PHP file except view templates, and declare types for parameters, return values and properties. Types belong in the signature — this is what makes [2.8](#2.8) work at runtime and removes most redundant PHPDoc (see [3.4](#3.4)).<br />
Use `void`, `?T`, union types and `never` where they describe the real contract.<br />
Narrow `mixed` at the boundary — request input, config, JSON, database rows, untyped libraries — with `is_*()`, `instanceof` or a typed accessor (Laravel: `$request->integer()`, `Config::string()`, …; CakePHP: `toInt()`, `toString()`, … from `Cake\Core`), and pass only typed values further in. PHPStan reports a missing type declaration, but at level 8 it does not check what you do with a `mixed` value (`$request->input()`, `config()`, `$this->request->getData()`, `Configure::read()`, `json_decode()`, …), so this part is checked in code review.<br />
Class constants are typed too (`public const int MAX_RETRY_COUNT = 3;`). Rector adds the type to private constants and to every constant of a `final` class; type the others yourself.
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
Promote constructor parameters instead of declaring the property and assigning it manually. Mark dependencies and value objects `readonly` when they must not change after construction. When every property of a class is `readonly`, mark the class itself `readonly` instead.<br />
Rector applies all three automatically.
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
Add 1 blank line **after** each `if` block and loop block, unless the block is the last statement of its enclosing block.<br />
Add 1 blank line **before** the `return` keyword, unless `return` is the first statement of its block (e.g. a guard clause).
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

**Type-Safe Comparisons**

Use `===` instead of `==`, `!==` instead of `!=`.<br />
When comparing two values, always ensure they are of the **same data type** to avoid unexpected results (e.g. `'1' === 1` is `false`). Convert the value once, at the boundary, with a typed accessor (`$request->integer()` in Laravel, `toInt()` in CakePHP) — or cast it only after validating it (`is_numeric()`): `(int) 'abc'` silently becomes `0`, and PHPStan at level 8 does not report it (see [2.4](#2.4)).<br />
Pass `true` as the third argument of `in_array()` / `array_search()` / `array_keys()` to force strict comparison.<br />
PHPStan reports every `==` / `!=` and every missing strict flag; Rector rewrites `==` to `===` only when both sides provably have the same type. The conversion itself is on you. Rector also turns `switch` (loose comparison) into `match` (strict comparison) — check the types when reviewing that diff.
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
They replace nested `null` checks and `isset()` ternaries. Note that `?->` stops the whole chain as soon as one link is `null` — do not use it to hide a value that should never be `null` (validate and fail early instead).<br />
PHPStan (level 8) reports calling a method or reading a property on a value that may be `null`, so a missing `null` check fails CI.
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
Write PHPDoc when it adds information the signature cannot express: a description, array shapes, `@throws`, or generics. **Do not** repeat types that are already declared in the signature ([2.4](#2.4)) — a duplicated type is one more thing that can go stale.<br />
Every `array`, `iterable` or generic type in a signature needs its element type in PHPDoc (`@param list<string> $paths`): PHPStan reports a bare `array` as a missing type (`missingType.iterableValue`).<br />
A blank line separates the description from the tags, and each group of tags from the next.
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

**Do not use `empty()`** — use `isset()` or an explicit check.

**isset()** checks whether the variable has been set — it returns `true` if the variable exists and its value is not `null`. That means `''`, `0`, `'0'`, `false` and `[]` are **set**, so `isset()` returns `true` for them.

**empty()** checks whether a variable is *empty*, and these are all empty: `''`, `0`, `0.0`, `'0'`, `null`, `false`, `[]` and a declared-but-unassigned variable. A legit `0` or `'0'` silently becomes "missing", which is why PHPStan reports every `empty()` call.

Use `isset()` when only "missing / null" matters. Otherwise say exactly what you mean: `=== null`, `=== ''`, `=== []`, `=== 0` or `count($items) === 0`.
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

- **Rector** rewrites code: constructor promotion, `readonly` properties and classes, `switch` → `match`, removing an `else` after a `return`, adding type declarations and class constant types. Its PHP 8.4 and 8.5 sets also migrate code to the new syntax: `?T` for a parameter whose default is `null`, `new Foo()->bar()`, simple `foreach` loops to `array_find()` / `array_any()` / `array_all()`, `array_first()` / `array_last()`, `#[\Override]` on a property that overrides a parent property, and replacements for what 8.5 deprecates (the backtick operator, `(integer)`-style casts, `case X;`, `__sleep()` / `__wakeup()`). It does not turn nested `if`s into guard clauses ([4.1](#4.1)) or nested null checks into `?->` ([2.9](#2.9)) — those stay manual. The Laravel template also loads the rector-laravel sets; CakePHP has no maintained Rector set for code quality, so the CakePHP template uses the generic sets only.
- **PHP-CS-Fixer** handles formatting and style, plus a few safe rewrites. Some of them overlap with Rector: `??` and `??=`, removing a useless `else` / `return`, removing PHPDoc tags that repeat the signature, and removing the parentheses around `new` (`new Foo()->bar()`). Both tools produce the same result, so the overlap is harmless — but a behaviour you want to turn off must be turned off in both (see [Disabling a rule](#disabling-a-rule)).
- **PHPStan** only *reports*. Both templates run **level 8** with `phpstan-strict-rules` and the framework's extension (Larastan for Laravel, `cakedc/cakephp-phpstan` for CakePHP), so missing type declarations and iterable element types, calls on a possibly-`null` value, loose comparison, missing strict flags, `empty()` and non-boolean conditions are all errors. PHPStan does not check operations on `mixed` at this level, so narrowing `mixed` ([2.4](#2.4)) is checked in code review.

The templates do not hardcode the PHP version: Rector's `withPhpSets()` reads `require.php` and PHPStan reads `config.platform.php` from `composer.json`, and PHP-CS-Fixer follows the PHP binary it runs on — so pin all three as in [4.7](#4.7).

Run them in that order — Rector, then PHP-CS-Fixer (which formats Rector's output), then PHPStan — see [Running all three](#running-all-three).<br />
Ready-to-use templates are provided per framework in [`config/php/laravel/`](./config/php/laravel) and [`config/php/cakephp/`](./config/php/cakephp). The PHP-CS-Fixer rules are identical in both — only the `Finder` paths differ.

| Laravel | CakePHP | Copy to project root as |
|---|---|---|
| [laravel/rector.template.php](./config/php/laravel/rector.template.php) | [cakephp/rector.template.php](./config/php/cakephp/rector.template.php) | `rector.php` |
| [laravel/.php-cs-fixer.dist.template.php](./config/php/laravel/.php-cs-fixer.dist.template.php) | [cakephp/.php-cs-fixer.dist.template.php](./config/php/cakephp/.php-cs-fixer.dist.template.php) | `.php-cs-fixer.dist.php` |
| [laravel/phpstan.dist.template.neon](./config/php/laravel/phpstan.dist.template.neon) | [cakephp/phpstan.dist.template.neon](./config/php/cakephp/phpstan.dist.template.neon) | `phpstan.dist.neon` |

Set up a project with the steps for its framework, then the [Shared steps](#shared-steps).

### Laravel setup

For Laravel 12+ (13 for a new project).

1. **Pin the PHP version** as in [4.7](#4.7). The skeleton ships `"php": "^8.3"`, and Rector's `withPhpSets()` takes the lowest version the constraint allows — without the pin, none of the PHP 8.4 / 8.5 rewrites run.

2. **Install packages**

   ```bash
   composer require --dev rector/rector driftingly/rector-laravel friendsofphp/php-cs-fixer phpstan/phpstan phpstan/phpstan-strict-rules larastan/larastan
   ```

   The PHPStan template is written for Larastan 3.x. Larastan is what lets PHPStan understand Eloquent models, facades and the container — without it, almost every model and facade call in a Laravel project is reported.

3. **Remove the tools that conflict with the templates**

   - Run `composer remove --dev laravel/pint`. The skeleton ships Pint, whose `laravel` preset puts the `{` of a method on its own line — the opposite of [2.1](#2.1). Two formatters would keep rewriting each other's output.
   - Delete any `phpstan.neon` / `phpstan.neon.dist`: PHPStan reads them before `phpstan.dist.neon`, so they would override the template.

4. **Copy the templates** from the Laravel column of the table above, then adjust `withPaths()` (Rector), the `Finder` (PHP-CS-Fixer) and `paths` / `excludePaths` (PHPStan) to your project layout.

5. Continue with the [Shared steps](#shared-steps).

### CakePHP setup

For CakePHP 5.3+. CakePHP 4.x and older need PHPStan 1.x, so the templates do not apply there — apply sections 1–4 only.

1. **Pin the PHP version** as in [4.7](#4.7). The skeleton ships `"php": ">=8.2"` — same reason as for Laravel.

2. **Install packages**

   ```bash
   composer require --dev rector/rector friendsofphp/php-cs-fixer phpstan/phpstan phpstan/phpstan-strict-rules cakedc/cakephp-phpstan dereuromark/cakephp-ide-helper
   bin/cake plugin load IdeHelper --only-cli --optional
   ```

   - `cakedc/cakephp-phpstan` (4.x) plays the role of Larastan: it types `fetchTable()`, `loadComponent()`, `Table::get()` / `newEntity()` / `patchEntity()` / `save()` and associations. It does **not** know the properties of an entity or `$this->Users` in a controller — those come from the `@property` / `@method` annotations that bake writes and IdeHelper keeps in sync (step 6).
   - It also enables its own rules: no `debug()` / `dd()` / `pr()` calls, no array access on entities, and checks on associations, behaviors, components, mailers and controller actions. To turn one off, set it to `false` under `parameters.cakeDC` (e.g. `disallowEntityArrayAccessRule: false`), following [Disabling a rule](#disabling-a-rule).

3. **Remove the skeleton's own tooling**

   - Delete `phpstan.neon`: PHPStan reads it before `phpstan.dist.neon`, so it would override the template.
   - Delete `psalm.xml` and `phpcs.xml`, and run `composer remove --dev cakephp/cakephp-codesniffer`. The CakePHP coding standard puts the `{` of a method on its own line — the opposite of [2.1](#2.1).
   - Remove the `check`, `cs-check` and `cs-fix` scripts from `composer.json`: `check` would clash with the one in the [Shared steps](#shared-steps). Keep `test`.
   - Delete the `.github/` folder. It belongs to the cakephp/app repository itself (issue templates, Dependabot), and its `ci.yml` runs phpcs. Write your own CI with `composer check` and `composer test`.

4. **Copy the templates** from the CakePHP column of the table above. They cover `config/`, `plugins/`, `src/` and `tests/`; if the project has no `plugins/` folder, remove it from all three.
   - `templates/` (and `plugins/*/templates/`) is excluded from all three tools: templates mix HTML and PHP and do not declare strict types.
   - The Rector template skips `ThrowWithPreviousExceptionRector`. CakePHP's `HttpException` takes the HTTP status as `$code`, so passing the caught exception's code turns a `throw new NotFoundException()` inside a `catch` into a 500 response.

5. **Configure IdeHelper** — add this block to `config/app.php`. Without it, the `@method` annotations on a table use a bare `array` for every parameter, and PHPStan reports around 20 `missingType.iterableValue` errors per table:

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

   Run `composer annotate` after every `bake` and every schema change. It reads the table schemas, so it needs a migrated database — which is why it is not part of `check`. The scripts call `@php bin/cake.php` rather than `bin/cake` so that they also run on Windows.<br />
   `reportMagicProperties: true` in the PHPStan template is what catches a missing annotation: without it, `$user->name` on an entity with no `@property` for `name` passes as `mixed`, and level 8 does not check it.

7. **CI** — PHPStan loads `config/bootstrap.php`, which needs a security salt. Without `config/app_local.php` (it is git-ignored) and without a `SECURITY_SALT` environment variable, `composer stan` stops with a `TypeError`. Run `composer install` with its scripts enabled (the skeleton's installer creates `app_local.php`), or set `SECURITY_SALT` in the CI job.

8. **Code from `bin/cake bake`** does not pass level 8 as is. After `composer fix`, fix by hand:
   - `if ($this->Users->save($user))` → `if ($this->Users->save($user) !== false)`: `save()` returns the entity or `false`, and only booleans are allowed in a condition.
   - the `@return \Cake\Http\Response|null|void` tag of `index()` / `view()`: Rector adds the `void` return type, and PHPStan reports that the tag no longer matches (`return.phpDocType`) — delete the tag.
   - `unset($this->Users)` in the `tearDown()` of a table test (`unset.possiblyHookedProperty`): declare the test class `final`.

   To upgrade CakePHP itself, use [`cakephp/upgrade`](https://github.com/cakephp/upgrade) (Rector-based) as a standalone application — not as a dev dependency of the project.

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

   What each script does: [Rector](#rector), [PHP-CS-Fixer](#php-cs-fixer), [PHPStan](#phpstan), [Running all three](#running-all-three).

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

Both templates are written for PHPStan 2.x; PHP 8.5 syntax needs PHPStan 2.1.32 or later.

Both templates turn off exactly one strict rule, `dynamicCallOnStaticMethod`: it reports PHPUnit's `$this->assertSame()` in every test, and in Laravel also macros such as `$request->validate()`.

- **composer stan** — analyses the codebase at the configured level and reports violations.
- **composer stan:baseline** — (re)generates `phpstan-baseline.neon`, freezing every current error so `composer stan` only fails on new ones. Run this once when adopting PHPStan on an existing codebase, then periodically to shrink the baseline. The baseline only takes effect once it is included in `phpstan.dist.neon`:

  ```neon
  includes:
      - phpstan-baseline.neon
  ```

#### Existing codebase

Every project runs **level 8** — do not lower or raise it. On an existing codebase, run `composer rector:fix` first — the type declarations it adds remove many missing-type errors — then `composer stan:baseline` to freeze the remaining errors, and shrink the baseline as you touch that code. Regenerate the baseline only to shrink it, never to absorb errors in new code.

#### VSCode extension *(optional)*

https://marketplace.visualstudio.com/items?itemName=SanderRonde.phpstan-vscode<br />
Shows PHPStan errors inline as you type, without waiting for `composer stan`.

### Running all three

Rector's output is not formatted to the house style, so PHP-CS-Fixer must run after it. The `fix` and `check` scripts from the [Shared steps](#shared-steps) fix that order once:

- **composer fix** — applies Rector's rewrites, then formats the result.
- **composer check** — dry-run of all three tools, changes nothing. Use this in CI, together with the test suite.

### Disabling a rule

Apart from `dynamicCallOnStaticMethod` in the PHPStan templates (see [PHPStan](#phpstan)) and `ThrowWithPreviousExceptionRector` in the CakePHP Rector template (see [CakePHP setup](#cakephp-setup)), none of the three tools should have a rule disabled by default — disabling is the exception, and the same discipline applies across PHP-CS-Fixer, Rector and PHPStan:

1. **Scope it as narrowly as possible** instead of turning a rule off project-wide:
   - **PHP-CS-Fixer** (no per-line disable comment) — exclude the single path with `$finder->notPath(...)` / `->exclude(...)`.
   - **Rector** (no per-line disable comment) — skip the single rule-and-path pair with `->withSkip([RuleClass::class => [__DIR__ . '/path/to/File.php']])`, the narrowest form; avoid removing the rule project-wide.
   - **PHPStan** (the one tool with a native per-line disable comment) — prefer `// @phpstan-ignore <identifier> (<reason>)` on the offending line over an `excludePaths` entry in `phpstan.dist.neon`, unless the exception spans a whole file. `@phpstan-ignore-line` / `@phpstan-ignore-next-line` are not allowed: they silence every error on the line and cannot carry a reason, so the template rejects them (`reportIgnoresWithoutComments: true`). For a type error, fix the type first (`is_*()`, `instanceof`, a `null` check, a typed accessor) — ignoring is the last resort.
   - The PHPStan `level` cannot be disabled or changed: it stays at 8 in every project (see [Existing codebase](#existing-codebase)).
   - A behaviour that both Rector and PHP-CS-Fixer implement (see [6. Implement Lint](#6-implement-lint)) must be turned off in both — for example, keeping an `else` after a `return` means skipping `RemoveAlwaysElseRector` **and** turning off `no_useless_else` / `no_superfluous_elseif`.

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

3. **Report the change to your PM/leader** before merging. A disabled rule without a written reason and without the PM being informed must be rejected in code review.
4. If the same rule keeps getting disabled across the project, raise it with the leader — revisit the rule instead of accumulating exceptions.

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
