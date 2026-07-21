# Web Security Rules

These security rules apply to all web projects, both **Node.js/TypeScript** and **PHP** (Laravel / CakePHP).

## Table of Contents

- [**1. Use parameterized queries**](#1)
- [**2. Choose Libraries with Proven Security and Reliability**](#2)
- [**3. Implement rate limiting**](#3)
- [**4. Use logging and monitoring**](#4)
- [**5. Use context-aware output encoding to prevent XSS**](#5)
- [**6. Avoid direct user input for files, redirects, URLs and commands**](#6)
- [**7. Validate input both client-side and server-side**](#7)
- [**8. Limit IP access to the database**](#8)
- [**9. Hash passwords, do not encrypt them**](#9)
- [**10. Remove unused dependencies**](#10)
- [**11. Hide version information in production**](#11)

## Security Rules

<table>
<tr>
<th>No</th>
<th>Rule</th>
<th>Priority</th>
<th>Example</th>
</tr>

<tr>
<td id='1'>

**1**
</td>

<td>

Use parameterized queries or ORM query builders that bind parameters safely. Never concatenate user input directly into SQL.
</td>

<td>

**REQUIRED**
</td>

<td>

**Node.js**

```typescript
// Bad
const query = `SELECT * FROM users WHERE id = ${userId}`;

// Good 👍 parameterized query
const query = 'SELECT * FROM users WHERE id = ?';
await db.query(query, [userId]);

// Good 👍 ORM/query builder example
await db
  .select()
  .from(users)
  .where(eq(users.id, userId));
```

**PHP**

```php
// Bad
$query = $this->query()->whereRaw("user.name LIKE {$nameInput}");

// Good 👍
$query = $this->query()->whereRaw('user.name LIKE ?', [$nameInput]);
```
</td>
</tr>

<tr>
<td id='2'>

**2**
</td>

<td>

Choose Libraries with Proven Security and Reliability.
</td>

<td>

**REQUIRED**
</td>

<td>

**For Open Source Libraries**:
- **Popularity & Downloads**: Use git platform stars(Github stars, Gitlab stars, etc.) and download counts (should be at least 1,000 downloads in the last 30 days) as an initial filter, but don’t rely solely on them.
- **Security Monitoring**: Look for any vulnerabilities in CVE databases and security alerts. Use Snyk ([npm](https://security.snyk.io/vuln/npm) / [composer](https://security.snyk.io/vuln/composer)) to check for vulnerabilities. Avoid libraries with unresolved or critical vulnerabilities.
- **Active Maintenance**: Check for recent commits, quick issue resolution, and active pull requests.
- **License Compliance**: Ensure the library uses a permissive, project-compatible license (e.g., MIT, Apache 2.0).
- **Audits & Testing**: Prefer libraries with external security audits or penetration testing, and those with high test coverage.
- **Code Quality**: Check for the use of linters, static analysis tools, and automated CI for code quality.
- **Consider Alternatives**: Look for community-maintained versions (forks) if the original library is no longer well-maintained but still widely used.

**For Third-Party Proprietary Libraries**:
- **Security Commitment Review**: Before integrating a third-party library, read and understand its security policies, data handling practices, and any security commitments to ensure they align with the project's security requirements and standards.

</td>
</tr>

<tr>
<td id='3'>

**3**
</td>

<td>

**Implement rate limiting.**
Implement rate limiting to prevent brute force attacks and other forms of abuse.
However, depend on your project large and the original design, you can choose apply this spec or not.
</td>

<td>

**RECOMMENDED**
</td>

<td>

**Node.js**

```typescript
import rateLimit from 'express-rate-limit';

const loginLimiter = rateLimit({
  windowMs: 60 * 1000,
  max: 10,
  standardHeaders: true,
  legacyHeaders: false,
});

app.post('/login', loginLimiter, loginController.login);
```

**PHP — Laravel**

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
<td id='4'>

**4**
</td>

<td>

**Use logging and monitoring.**
Best way to know if any error on your server.
</td>

<td>

**RECOMMENDED**
</td>

<td>

**Node.js**

```typescript
// Bad example: no logging or monitoring
app.post("/data", (req, res) => {
  //store data in database
});

// Good example: implementing logging and monitoring 👍
const winston = require("winston");
const logger = winston.createLogger({
  level: "info",
  format: winston.format.json(),
  defaultMeta: { service: "my-app" },
  transports: [
    new winston.transports.File({ filename: "error.log", level: "error" }),
    new winston.transports.File({ filename: "combined.log" })
  ]
});
app.post("/data", (req, res) => {
  logger.info("Data received", { payload: req.body });
  // store data in database
});
```

**PHP — Laravel**

```php
// Bad example: no logging or monitoring
use Illuminate\Http\Request;

public function handleData(Request $request) {
    // Store data in database
}

// Good example: implementing logging and monitoring 👍
use Illuminate\Http\Request;

public function handleData(Request $request) {
    Log::info('Data received');
    Log::info($request->all());
    // Store data in database
}
```
</td>
</tr>

<tr>
<td id='5'>

**5**
</td>

<td>

Use context-aware output encoding to prevent XSS. Do not manually insert untrusted input into HTML, JavaScript, CSS or URLs. When rendering user-provided rich text, sanitize it with an approved sanitizer.

In Laravel, use Blade `{{ }}` statements — output is automatically sent through PHP's `htmlspecialchars` function.
In CakePHP, output is not automatically escaped, so escape manually with the `h()` function.
</td>

<td>

**REQUIRED**
</td>

<td>

**Node.js**

```typescript
// Bad
res.send(`<div>${req.query.name}</div>`);

// Better 👍 template engines/frameworks usually escape by default
res.render('profile', {
  name: user.name,
});

// If HTML is intentionally allowed, sanitize it first.
const safeHtml = sanitizeHtml(userProvidedHtml);
```

**PHP — Laravel**

```php
// Bad
<?= $userName; ?>
{!! $userName !!}

// Good 👍
{{ $userName }}
```

**PHP — CakePHP**

```php
// Bad
<?= $userName; ?>

// Good 👍
<?= h($userName); ?>
```
</td>
</tr>

<tr>
<td id='6'>

**6**
</td>

<td>

Avoid using direct user input for file paths, redirects, outbound URLs or shell commands. Use IDs, allowlists and safe mapping functions.
</td>

<td>

**REQUIRED**
</td>

<td>

**Node.js**

```typescript
// Bad: open redirect
res.redirect(req.body.redirectUrl);

// Good 👍
const redirectUrl = getAllowedRedirectUrl(req.body.redirectUrlId);
res.redirect(redirectUrl);

// Bad: path traversal risk
const file = await fs.readFile(req.body.filePath);

// Good 👍
const file = await fileStorage.readById(req.body.fileId, currentUser.companyNo);
```

**PHP**

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
<td id='7'>

**7**
</td>

<td>

**Validate input both client-side and server-side.**
Client-side validation can be bypassed by attackers and may not catch all issues.
Server-side validation is more reliable as it's performed on the server and can catch potential issues missed by client-side validation.
By validating input on both the client-side and server-side, we can ensure data submitted by users is safe, complete, and accurate.
</td>

<td>

**REQUIRED**
</td>

<td>

**Node.js** — validate the request body with a schema validator (e.g. [zod](https://zod.dev/))

```typescript
import { z } from 'zod';

const createPostSchema = z.object({
  title: z.string().min(1).max(255),
  body: z.string().min(1),
});

app.post('/posts', (req, res) => {
  const result = createPostSchema.safeParse(req.body);
  if (!result.success) {
    return res.status(422).json({ errors: result.error.flatten() });
  }
  // use result.data — validated and typed
});
```

**PHP — Laravel** — use [Form Request Validation](https://laravel.com/docs/validation#form-request-validation)

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

**PHP — CakePHP** — use [Validation Set](https://book.cakephp.org/latest/en/orm/validation.html#using-a-different-validation-set)

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
<td id='8'>

**8**
</td>

<td>

**Should** limit IP access to the database, such as setting an IP limit on AWS, Azure, etc.
</td>

<td>

**RECOMMENDED**
</td>

<td>

```typescript
Example setting with AWS services:
- Go to the AWS Management Console 
and open the Amazon RDS console.
- Select the RDS instance for which 
you want to limit IP access.
- In the "Details" tab, locate the "Security group rules" section 
and click the name of the security group.
- In the "Inbound rules" tab, click "Edit inbound rules".
- Under "Type", select "MySQL/Aurora" 
(or the appropriate database engine).
- Under "Source", select "Custom" and enter the IP address \
or range that you want to allow access from.
- Click "Save rules".

```
</td>
</tr>

<tr>
<td id='9'>

**9**
</td>

<td>

Hash passwords. Do not encrypt passwords with reversible encryption. Use a strong password hashing algorithm such as Argon2id, bcrypt or PBKDF2 depending on project requirements.

Other sensitive information that must be stored in the database and read back later (not passwords) should be encrypted.
</td>

<td>

**REQUIRED**
</td>

<td>

**Node.js**

```typescript
import bcrypt from 'bcrypt';

const SALT_ROUNDS = 12;

export async function hashPassword(password: string): Promise<string> {
  return bcrypt.hash(password, SALT_ROUNDS);
}

export async function verifyPassword(
  password: string,
  passwordHash: string,
): Promise<boolean> {
  return bcrypt.compare(password, passwordHash);
}
```

**PHP — Laravel**

```php
use Illuminate\Support\Facades\Hash;

$password = 'Your Password';
$hashedPassword = Hash::make($password);
```

**PHP — CakePHP**

```php
use Cake\Auth\DefaultPasswordHasher;

$password = 'Your Password';
$hasher = new DefaultPasswordHasher();
$hashedPassword = $hasher->hash($password);
```
</td>
</tr>

<tr>
<td id='10'>

**10**
</td>

<td>

**Remove unused dependencies.**
Any package declared in `package.json` / `composer.json` that the project no longer uses, and any manually-managed JS/CSS library files left in the webroot, must be removed.
Unused dependencies enlarge the attack surface and keep shipping code that may contain known vulnerabilities.
</td>

<td>

**REQUIRED**
</td>

<td>

**Node.js** — detect and remove unused npm packages with [knip](https://github.com/webpro-nl/knip)

```bash
# Detect unused dependencies
npx knip

# Remove them
npm uninstall <package-name>
```

**PHP** — detect and remove unused composer packages

```bash
# Install once
composer global require icanhazstring/composer-unused

# Detect unused dependencies (run in the project root)
composer-unused

# Remove them
composer remove <package-name>
```

**Manually-managed libraries** — also delete unused library folders that are not managed by any package manager (e.g. an abandoned `webroot/js/jquery/` or `public/vendor/datatables/` no longer referenced by any page).

> **Note:** both tools rely on static analysis and can report **false positives** — packages loaded dynamically, CLI-only tools, or plugins referenced only in config files may be flagged as unused. Verify each finding before removing.
</td>
</tr>

<tr>
<td id='11'>

**11**
</td>

<td>

**Hide version information in production.**
Servers, frameworks and libraries must not disclose their versions through HTTP headers, HTML meta tags, JS/CSS banner comments, source maps or files exposed in the webroot.
Version disclosure lets attackers fingerprint the stack and target known CVEs.
</td>

<td>

**REQUIRED**
</td>

<td>

Follow the [Hide Version Information Guideline](./Guidelines/HideVersionInfoGuideline.md) for per-technology configuration.
</td>
</tr>
</table>

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
