# PHP Coding Rules

## Table of Contents

[**1. Naming**](#1-naming)

- [1.1 Name of files, namespaces, classes, interfaces, enums and traits](#1.1)
- [1.2 Names of functions, properties and variables](#1.2)
- [1.3 Names of constants](#1.3)
- [1.4 Use meaningful variable names](#1.4)
- [1.5 Starting a boolean variable or property with a question words](#1.5)

[**2. Styling**](#2-styling)

- [2.1 Class layout](#2.1)
- [2.2 Prefer a maximum line length](#2.2)
- [2.3 Use single quotes for string literals](#2.3)
- [2.4 Use 4 whitespaces for indentation](#2.4)
- [2.5 Multi-line arrays, arguments list, parameters list and match expressions must have a trailing comma](#2.5)
- [2.6 There must not be more than one statement per line](#2.6)
- [2.7 Use curly braces for all flow control statements](#2.7)

[**3. Comment**](#3-comment)

- [3.1 Single-line comment](#3.1)
- [3.2 Multi-line comments](#3.2)
- [3.3 PHPDoc comments](#3.3)
- [3.4 Use English for comment](#3.3)

[**4. Usage**](#4-usage)

- [4.1 PHP arrays should be declared using the short syntax](#4.1)
- [4.2 Should use explicit variables](#4.2)
- [4.3 Add curly braces to indirect variables](#4.3)
- [4.4 When we have to meet certain criteria to continue execution, try to exit early](#4.4)
- [4.5 Need to distinguish between `isset()` and `!empty()`](#4.5)
- [4.6 Converts simple usages of `array_push($x, $y);` to `$x[] = $y;`](#4.6)
- [4.7 Logical NOT operators (!) should have one trailing whitespace](#4.7)
- [4.8 The same namespaces must be grouped](#4.8)

[**5. Security**](#5-security)

- [5.1 Use parameterized queries](#5.1)
- [5.2 Use libraries with a good security track record](#5.2)
- [5.3 Implement rate limiting](#5.3)
- [5.4 Use logging and monitoring](#5.4)
- [5.5 Escape data HTML](#5.5)
- [5.6 Avoid using input from user to handle files / redirect url etc...](#5.5)
- [5.7 Validating input both client-side and server-side](#5.7)
- [5.8 Should encrypt sensitive information if it is necessary to store them in a database](#5.8)

[**6. Implement Lint**](#6-implement-lint)

- [Step 1: Install package](#step-1)
- [Step 2: Create .php-cs-fixer.dist.php put in the root directory](#step-2)
- [Step 3: Add scripts to composer.json](#step-3)
- [Step 4: Run composer commands](#step-4)

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

## 1. Naming
<table>
<tr>
<td id='1.1'>

**1.1**

</td>

<td>

Name of files, namespaces, classes, interfaces, enums and traits use **UpperCamelCase** format.

</td>

<td>

```php
UserController.php

namespace App\Http\Controllers;

class UserController
{
    // ...
}

interface Rule
{
    // ...
}

enum UserType
{
    case Admin;
    case Support;
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

Names of functions, properties and variables use **lowerCamelCase** format.

</td>

<td>

```php
function redirectTo($request) {
    // ...
}

$routeName = 'abc';
```

</td>
</tr>

<tr>
<td id='1.3'>

**1.3**

</td>

<td>

Names of constants use **UPPER_CASE_UNDERSCORE** format.

</td>

<td>

```php
const TABLE_NAME = 'users';
```

</td>
</tr>

<tr>
<td id='1.4'>

**1.4**

</td>
<td>

Use meaningful variable names.

</td>
<td>

```php
// Bad
$fn = 'John';
$a = 20;

// Good 👍                                                  
$firstName = 'John';
$age = 20;
```

</td>
</tr>

</tr>
<tr>
<td id='1.5'>

**1.5**

</td>
<td> 

Starting a boolean variable or property with a question words like *can, is, should*,...

</td>
<td>

```php
$isConnected = true;
$shouldConfirm = true;
$canResize = true;
```

</td>
</tr>
</table>
<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br />

## 2. Styling
<table>

<tr>
<td id='2.1'>

**2.1**

</td>
<td>

**Class layout**<br />
Orders the elements in class:
- Use trait
- 1 line break
- Constant (with order public -> protected -> private)
- 1 line break
- Property (with order public -> protected -> private)
- 1 line break
- Construct
- 1 line break
- Destruct
- 1 line break
- Magic method
- 1 line break
- Method (with order public -> protected -> private)
- 1 line break each method

</td>
<td>

```php
class Example
{
    use BarTrait;
    use BazTrait;

    public const PUBLIC_CONSTANT = 1;
    protected const PROTECTED_CONSTANT = 2;
    private const PRIVATE_CONSTANT = 2;

    public $pubicProperty;
    protected $protectedProperty;
    private $privateProperty;

    public function __construct() {}

    public function __destruct() {}

    public function __toString() {}

    public function pubicFunction() {}

    public static function pubicStaticFunction() {}

    protected function protectedFunction() {}

    protected static function protectedStaticFunction() {}

    private function privateFunction() {}

    private static function privateStaticFunction() {}
}

```

</td>
</tr>

<tr>
<td id='2.2'>

**2.2**

</td>
<td>

	
**Prefer a maximum line length of 80 characters**<br />
When the line exceeds column limit, it must be wrapped by follow conventions:
-	Break after a comma.
-	Break before an operator.

</td>
<td>

```php
if (
    ($condition1 === true || $condition2 > 0)
    && $condition3 === false // Break before an operator
    && $condition4 === 1
) {
    callSomeThing(
        $longNameParam, // Break after a comma
        $otherLongNameParam,
    );
}
```

</td>
</tr>

<tr>
<td id='2.3'>

**2.3**

</td>
<td>

Use single quotes for string literals unless we need to include a single quote or variables within the string.

</td>
<td>

```php
// Bad
$message = "Hello world";

// Good👍
$message1 = 'Hello world';
$message2 = "Hello {$name}";
```

</td>
</tr>

<tr>
<td id='2.4'>

**2.4**

</td>
<td>

Use 4 whitespaces for indentation instead of tabs.

</td>
<td>

```php
function multiplyNumbers($a, $b) {
    $result = $a * $b;

    return $result;
}
```

</td>
</tr>

<tr>
<td id='2.5'>

**2.5**

</td>
<td>

Multi-line arrays, arguments list, parameters list and match expressions must have a trailing comma (**include last item**).

</td>
<td>

```php
// Array
$foo = [
    'bar' => [
        'baz' => true,
        'baq' => true,
    ],
    'bas' => [
        'bak' => false,
    ],
];

// Arguments list
foo(
    'bar',
    'baz',
);

// Parameters list
function foo(
    $x,
    $y,
) {
    // ...
}

// Match expressions
$returnValue = match ($food) {
    'apple' => 'This food is an apple',
    'bar' => 'This food is a bar',
    'cake' => 'This food is a cake',
};
```

</td>
</tr>

<tr>
<td id='2.6'>

**2.6**

</td>
<td>

There must not be more than one statement per line.

</td>
<td>

```php
// Bad
foo(); bar();

// Good 👍
foo();
bar();
```

</td>
</tr>

<tr>
<td id='2.7'>

**2.7**

</td>
<td>

Use curly braces for all flow control statements.

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
</table>
<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br />

## 3. Comment

Commenting code is an important aspect of software development as it helps other developers understand your code and makes it easier to maintain.

<table>
<tr>
<td id='3.1'>

**3.1**

</td>

<td>

**Single-line comment**<br />
Format comments like sentences, begin with 1 whitespace and capitalize the first word.

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
<td id='3.2'>

**3.2**

</td>
<td>

**Multi-line comments**<br />
Note that all "*" should be aligned.

</td>

<td>

```php
/*
 * This is a multi-line comment.
 * It can be used to explain large sections of code.
 */
$age = 30;
```

</td>
</tr>

<tr>
<td id='3.3'>

**3.3**

</td>
<td>

**PHPDoc comments**

</td>
<td>

```php
/**
 * This is a PHPDoc comment.
 * There should be a line break between Descriptions and Tags.
 *
 * @param \Illuminate\Http\Request $request
 * @return string|null
 */
protected function redirectTo($request) {
    // ...
}
```

</td>
</tr>

<tr>
<td id='3.4'>

**3.4**

</td>
<td>

**USE** English for comment.

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
</table>
<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br />

## 4. Usage
<table>

<tr>
<td id='4.1'>

**4.1**

</td>
<td>

PHP arrays should be declared using the **short** syntax instead of long syntax.

</td>
<td>

```php
// Bad
$numbers = array(1, 2);

// Good 👍
$numbers = [1, 2];
```

</td>
</tr>

<tr>
<td id='4.2'>

**4.2**

</td>
<td>

We should use **explicit** variables instead of implicit in double-quoted strings.

</td>
<td>

```php
// Bad
$name = 'World';
$message = "Hello $name";

// Good👍
$name = 'World';
$message = "Hello {$name}";
```

</td>
</tr>

<tr id="4.3">
<td>

**4.3**

</td>
<td>

Add curly braces to indirect variables to make them clear to understand. 

</td>
<td>

```php
// Bad
echo $$foo;
echo $$foo['bar'];
echo $foo->$bar['baz'];
echo $foo->$callback($baz);

// Good 👍
echo ${$foo};
echo ${$foo}['bar'];
echo $foo->{$bar}['baz'];
echo $foo->{$callback}($baz);
```
</td>
</tr>

<tr id="4.4">
<td>

**4.4**

</td>
<td>

When we have to meet certain criteria to continue execution, try to exit early.

</td>
<td>

```php
// Bad
if ($isTrue) {
    // ...
}else {
    return;
}

// Good 👍
if (! $isTrue) {
  return;
}
```
</td>
</tr>

<tr id="4.5">
<td>

**4.5**

</td>
<td>

Need to distinguish between `isset()` and `!empty()`.

</td>
<td>

**ISSET** checks the variable to see if it has been set.
In other words, it checks to see if the variable is any value except `null` or `not assigned a value`.<br />
**ISSET** returns `true` if the variable exists and has a value other than `null`.<br />
That means variables assigned a `""`, `0`, `"0"`, or `false` are set, and therefore are `true` for **ISSET**.

**EMPTY** checks to see if a variable is `empty`.<br />
Empty is interpreted as: `""` (an empty string), `0` (integer), `0.0` (float), `"0"` (string), `null`, `false`, `[]` (an empty array), and `$var;` (a variable declared, but without a value in a class).

</td>
</tr>

<tr id="4.6">
<td>

**4.6**

</td>
<td>

Converts simple usages of `array_push($x, $y);` to `$x[] = $y;`.

</td>
<td>

```php
// Bad
$animals = [
    'tiger',
    'lion',
    'dog',
];
array_push($animals, 'cat');

// Good 👍
$animals = [
    'tiger',
    'lion',
    'dog',
];
$animals[] = 'cat';
```
</td>
</tr>

<tr id="4.7">
<td>

**4.7**

</td>
<td>

Logical NOT operators (!) should have one trailing whitespace.

</td>
<td>

```php
// Bad
if (!$bar) {
    echo 'Help!';
}

// Good 👍
if (! $bar) {
    echo 'Help!';
}
```
</td>
</tr>

<tr id="4.8">
<td>

**4.8**

</td>
<td>

The same namespaces must be grouped.

</td>
<td>

```php
// Bad
use Foo\Bar;
use Foo\Baz;

// Good 👍
use Foo\{Bar, Baz};
```
</td>
</tr>

</table>

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br />

## 5. Security

<table>

<tr>
<td id='5.1'>

**5.1**

</td>
<td>

**Use parameterized queries**<br />
Depend on your ORM framework, always ensure that the method supports binding parameters.
(Prevent SQL injection attacks)

</td>
<td>

```php
// Bad
$query = $this->query()->whereRaw("user.name LIKE {$nameInput}");

// Good 👍
$query = $this->query()->whereRaw('user.name LIKE ?', [$nameInput]);
```

</td>
</tr>

<tr>
<td id='5.2'>

**5.2**

</td>
<td>

**Use libraries with a good security track record.**
You can check rating star on Github or user downloaded, should choose libraries with high ratings to use.

</td>
<td>

```php
// No example
```
</td>
</tr>

<tr>
<td id='5.3'>

**5.3**

</td>
<td>

**Implement rate limiting.**
Implement rate limiting to prevent brute force attacks and other forms of abuse.
However, depend on your project large and the original design, you can choose apply this spec or not.
</br>

</td>
<td>

```php
// Config Rate limit for all /api route

// app\Providers\RouteServiceProvider.php
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

public function boot(): void {
    RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    });
    // ...
}
```
</td>
</tr>

<tr>
<td id='5.4'>

**5.4**

</td>
<td>

**Use logging and monitoring.**
Best way to know if any error on your server.
</td>
<td>

```php
// Bad example: no logging or monitoring
use Illuminate\Http\Request;

public function handleData(Request $request) {
    // Store data in database
}

// Good example: implementing logging and monitoring 👍
use Illuminate\Http\Request;

public function handleData(Request $request) {
    Log::info("Data received");
    Log::info($request->all());
    // Store data in database
}
```
</td>
</tr>

<tr>
<td id='5.5'>

**5.5**

</td>
<td>

**Escape data HTML**<br />
In Laravel, use Blade `{{ }}` statements to automatically sent through PHP's htmlspecialchars function to prevent XSS attacks.<br />
In CakePHP, it does not automatically escape output so we have to manual escape with the `h()` function.

</td>
<td>

Laravel

```php
// Bad
<?= $userName; ?>
{!! $userName !!}

// Good 👍
{{ $userName }}
```

CakePHP

```php
// Bad
<?= $userName; ?>

// Good 👍
<?= h($userName); ?>
```

</td>
</tr>

<tr>
<td id='5.6'>

**5.6**

</td>
<td>

**Avoid** using input from user to handle files / redirect url etc... <br>
Can use ID for finding specific file or url.
</td>
<td>

```php
// Bad
return redirect($request->input('hiddenInputUrl'));

// Bad
$dataFileDetail = Storage::get($request->input('filePath'));

// Good 👍
$redirectUrl = getUrlFromInputId($request->input('hiddenInputUrlId'));
return redirect($redirectUrl);
```
</td>
</tr>

<tr>
<td id='5.7'>

**5.7**

</td>
<td>

**Validating input both client-side and server-side**<br />
Client-side validation can be bypassed by attackers and may not catch all issues.<br />
Server-side validation is more reliable as it's performed on the server and can catch potential issues missed by client-side validation.<br />
By validating input on both the client-side and server-side, we can ensure data submitted by users is safe, complete, and accurate.
</td>
<td>

In Laravel use [Form Request Validation](https://laravel.com/docs/10.x/validation#form-request-validation)

```php
/**
 * Get the validation rules that apply to the request.
 *
 * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
 */
public function rules(): array {
    return [
        'title' => 'required|unique:posts|max:255',
        'body' => 'required',
    ];
}
```

In CakePHP use [Validation Set](https://book.cakephp.org/4/en/orm/validation.html#using-a-different-validation-set)

```php
class ArticlesTable extends Table
{
    public function validationUpdate($validator) {
        $validator
            ->notEmptyString('title', __('You need to provide a title'))
            ->notEmptyString('body', __('A body is required'));
        return $validator;
    }
}

$article = $this->Articles->newEntity(
    $this->request->getData(),
    ['validate' => 'update'],
);
```

</td>
</tr>

<tr>
<td id='5.8'>

**5.8**

</td>

<td>

**Should** encrypt sensitive information if it is necessary to store them in a database.<br>
Such as using Bcrypt to encrypt password.<br>
Both Laravel and CakePHP support Bcrypt out of the box.
</td>
<td>

Laravel

```php
use Illuminate\Support\Facades\Hash;

$password = "Your Password";
$hashedPassword = Hash::make($password);
```

CakePHP

```php
use Cake\Auth\DefaultPasswordHasher;

$password = "Your Password";
$hasher = new DefaultPasswordHasher();
$hashedPassword = $hasher->hash($password);
```

</td>
</tr>

</table>

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br />

## 6. Implement Lint

We will implement PHP lint using PHP Coding Standards Fixer.<br />
Ref: https://github.com/PHP-CS-Fixer/PHP-CS-Fixer

#### Step 1
**Install package**
```php
composer require --dev friendsofphp/php-cs-fixer
```

#### Step 2

**Create .php-cs-fixer.dist.php put in the root directory**

```php
<?php

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'align_multiline_comment' => true,
        'array_indentation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => true,
        'blank_line_before_statement' => ['statements' => ['return']],
        'cast_spaces' => true,
        'class_attributes_separation' => true,
        'class_reference_name_casing' => true,
        'clean_namespace' => true,
        'concat_space' => ['spacing' => 'one'],
        'control_structure_braces' => true,
        'control_structure_continuation_position' => true,
        'curly_braces_position' => ['functions_opening_brace' => 'same_line'],
        'echo_tag_syntax' => ['format' => 'short'],
        'explicit_indirect_variable' => true,
        'explicit_string_variable' => true,
        'fully_qualified_strict_types' => true,
        'function_typehint_space' => true,
        'global_namespace_import' => true,
        'include' => true,
        'linebreak_after_opening_tag' => true,
        'list_syntax' => true,
        'lowercase_cast' => true,
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        'method_chaining_indentation' => true,
        'multiline_comment_opening_closing' => true,
        'multiline_whitespace_before_semicolons' => true,
        'native_function_casing' => true,
        'native_function_type_declaration_casing' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'curly_brace_block',
                'extra',
                'parenthesis_brace_block',
                'square_brace_block',
                'throw',
                'use',
            ],
        ],
        'no_leading_namespace_whitespace' => true,
        'no_mixed_echo_print' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_multiple_statements_per_line' => true,
        'no_short_bool_cast' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_spaces_around_offset' => true,
        'no_superfluous_elseif' => true,
        'no_trailing_comma_in_singleline' => true,
        'no_unset_cast' => true,
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_whitespace_before_comma_in_array' => true,
        'not_operator_with_successor_space' => true,
        'object_operator_without_whitespace' => true,
        'operator_linebreak' => ['only_booleans' => true],
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'case',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
                'magic',
                'phpunit',
                'method_public',
                'method_protected',
                'method_private',
            ],
        ],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'phpdoc_add_missing_param_annotation' => ['only_untyped' => false],
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_indent' => true,
        'phpdoc_line_span' => true,
        'phpdoc_order' => true,
        'phpdoc_trim' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types' => true,
        'phpdoc_types_order' => [
            'null_adjustment' => 'always_last',
            'sort_algorithm' => 'none',
        ],
        'phpdoc_var_annotation_correct_order' => true,
        'phpdoc_var_without_name' => true,
        'semicolon_after_instruction' => true,
        'simple_to_complex_string_variable' => true,
        'single_class_element_per_statement' => true,
        'single_import_per_statement' => false,
        'group_import' => true,
        'single_line_comment_spacing' => true,
        'single_line_comment_style' => ['comment_types' => ['hash']],
        'single_quote' => true,
        'single_space_around_construct' => true,
        'space_after_semicolon' => ['remove_in_empty_for_expressions' => true],
        'standardize_not_equals' => true,
        'statement_indentation' => true,
        'trailing_comma_in_multiline' => [
            'elements' => [
                'arguments',
                'arrays',
                'match',
                'parameters',
            ],
        ],
        'trim_array_spaces' => true,
        'types_spaces' => true,
        'unary_operator_spaces' => true,
        'whitespace_after_comma_in_array' => ['ensure_single_space' => true],
    ])
    ->setLineEnding("\n");

```

#### Step 3
**Add scripts to composer.json**

```json
"scripts": {
    "lint": "./vendor/bin/php-cs-fixer fix . --dry-run --verbose --config=.php-cs-fixer.dist.php",
    "lint-and-fix": "./vendor/bin/php-cs-fixer fix . --verbose --config=.php-cs-fixer.dist.php"
},
```

#### Step 4
**Run composer commands** 

`composer lint`: Command to check lint errors.<br />
`composer lint-and-fix`: Command to check and fix lint errors.

**Visual Studio Code extension**

https://marketplace.visualstudio.com/items?itemName=junstyle.php-cs-fixer<br />
This extension simply provides PHP CS Fixer command (include code format).

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
