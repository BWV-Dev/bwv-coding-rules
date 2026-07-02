
# NodeJs Coding Rules & Security (BWV)
## Table of Contents
[**Common** ](#common)
<br>

[**1. Naming** ](#1-naming)
- [1.1 Use camelCase for variables, functions, methods and object properties.](#1.1)
- [1.2 Use meaningful variable names](#1.2)
- [1.3 Avoid overly long or overly short names](#1.3)
- [1.4 Use boolean names that describe state or capability](#1.4)
- [1.5 Do not use unclear abbreviations in variable names](#1.5)
- [1.6 Use PascalCase for classes, interfaces, type aliases and enums](#1.6)
- [1.7 Use UPPER_CASE only for module-level constants](#1.7)
- [1.8 Do not use data type prefixes in variable names](#1.8)
- [1.9 Use const or let instead of var](#1.9)
- [1.10 Avoid using variable names that are identical to keywords in the programming language or libraries](#1.10)

[**2. Styling & TypeScript** ](#2-styling--typescript)
- [2.1 Let Prettier handle formatting rules](#2.1)
- [2.2 Type function parameters explicitly](#2.2)
- [2.3 Declare return types for exported or complex functions](#2.3)
- [2.4 Use optional chaining and nullish coalescing where appropriate](#2.4)
- [2.5 Use async/await instead of callbacks wherever possible](#2.5)
- [2.6 Use interface and type based on the use case](#2.6)
- [2.7 Avoid non-null assertion unless there is a clear reason](#2.7)

[**3. Comment** ](#3-comment)
- [3.1 Comments should explain why, not repeat what the code does](#3.1)  
- [3.2 Single-line comments](#3.2)  
- [3.3 Multi-line comments](#3.3)  
- [3.4 JSDoc comments](#3.4) 
- [3.5 TODO and FIXME comments](#3.5) 

[**4. Usage & Code Quality** ](#4-usage--code-quality)
- [4.1 Prefer native JavaScript/TypeScript APIs before adding utility libraries](#4.1) 
- [4.2 no-unused-vars](#4.2) 
- [4.3 no-console](#4.3) 
- [4.4 no-debugger](#4.4) 
- [4.5 no-var and prefer-const](#4.5) 
- [4.6 sort-import](#4.6) 
- [4.7 no-explicit-any](#4.7) 
- [4.8 Importing specific functions](#4.8)
- [4.9 Maximum number of lines per file](#4.9)
- [4.10 no-floating-promises](#4.10)

[**5. Security** ](#5-security)
- [5.1 Use parameterized queries](#5.1) 
- [5.2 Choose Libraries with Proven Security and Reliability](#5.2)
- [5.3 Implement rate limiting](#5.3) 
- [5.4 Use logging and monitoring](#5.4) 
- [5.5 Use context-aware output encoding to prevent XSS](#5.5) 
- [5.6 Avoid direct user input for files, redirects, URLs and commands](#5.6) 
- [5.7 Should limit IP access to the database](#5.7) 
- [5.8 Hash passwords, do not encrypt them](#5.8) 

[**6. Implement Lint** ](#6-implement-lint)

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

## Common 
- Always check wiki on redmine
- Always prioritize the coding rules of the project, follow the conventions of your project
- The following coding rules have been applied in some projects, depending on the project's style, the leader will select and apply them differently
 
<br>

## 1. Naming
The good way to name folders, classes, types, variables and functions in Typescript.

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

Use **camelCase** for variables, functions, methods and object properties.
</td>

<td>

**REQUIRED**
</td>

<td >

```typescript
// Bad
let first_name = 'John';
function get_user_name() {}

// Good 👍
let firstName = 'John';
function getUserName() {}
```

</td>
</tr>

<tr>
<td id='1.2'>

**1.2**
</td>

<td>

Use meaningful variable names.
</td>

<td>

**REQUIRED**
</td>

<td>

```typescript
// Bad
let a = await repo.find();
let b = true;

// Good 👍
const residents = await residentRepository.findMany();
const isResidentActive = true;
```

</td>
</tr>

<tr>
<td id='1.3'>

**1.3**
</td>

<td>

Avoid overly long or overly short names. Long names often mean the function/object is doing too much.
</td>

<td>

**RECOMMENDED**
</td>

<td>

```typescript
// Bad
const thisIsTheResidentNameReturnedFromTheDatabaseAfterSearch = 'John';
const r = 'John';

// Good 👍
const residentName = 'John';
```

</td>
</tr>

<td id='1.4'>

**1.4**
</td>

<td>

Boolean variables should start with words that describe state, capability or intention.
</td>

<td>

**REQUIRED**
</td>

<td>

```typescript
const isConnected = true;
const hasPermission = true;
const canResize = false;
const shouldConfirm = true;
```

</td>
</tr>
<tr>
<td id='1.5'>

**1.5**
</td>

<td>

**Do not** use unclear abbreviations in variable names
</td>

<td>

**REQUIRED**
</td>

<td>

```typescript
// Bad
let fn = 'John';

// Good 👍
let firstName = 'John';

```

</td>
</tr>

<tr>
<td id='1.6'>

**1.6**
</td>

<td>

Use **PascalCase** for classes, interfaces, type aliases and enums.
</td>

<td>

**REQUIRED**
</td>

<td>

```typescript
// Bad
class residentService {}
interface residentDto {}
type residentStatus = 'active' | 'inactive';

// Good 👍
class ResidentService {}
interface ResidentDto {}
type ResidentStatus = 'active' | 'inactive';

enum PaymentStatus {
  Paid = 'paid',
  Unpaid = 'unpaid',
}
```

</td>
</tr>

<tr>
<td id='1.7'>

**1.7**
</td>

<td>

Use **UPPER_CASE** only for module-level constants or configuration values that behave like real constants. Do not use UPPER_CASE for every local const.
</td>

<td>

**REQUIRED**
</td>

<td>

```typescript
// Good 👍
const DEFAULT_PAGE_SIZE = 50;
const MAX_RETRY_COUNT = 3;

function buildResidentName(resident: Resident) {
  // Good 👍 local const should stay camelCase
  const residentName = `${resident.firstName} ${resident.lastName}`;
  return residentName;
}
```

</td>
</tr>

<tr>
<td id='1.8'>

**1.8**
</td>

<td> 

Do not use _ to mark private fields. Use TypeScript private, protected or JavaScript private fields instead. _ is acceptable for intentionally unused parameters when ESLint is configured for it.
</td>

<td>

**RECOMMENDED**
</td>

<td>

```typescript
// Bad
class UserService {
  private _token = '';
}

// Good 👍
class UserService {
  private token = '';
}

// Acceptable 👍 intentionally unused parameter
function handleError(_err: unknown, req: Request, res: Response) {
  res.status(500).json({ message: 'Internal server error' });
}
```
</td>
</tr>

<tr>
<td id='1.9'>

**1.9**
</td>

<td>

Use **const** by default. Use **let** only when reassignment is needed. Do not use **var**.
</td>

<td>

**REQUIRED**
</td>

<td>

```typescript
// Bad
var count = 1;

// Good 👍
const DEFAULT_LIMIT = 50;
let retryCount = 0;
retryCount += 1;
```
</td>
</tr>

<tr>
<td id='1.10'>

**1.10**
</td>

<td>

**Avoid** using variable names that are identical to keywords in the programming language or libraries
</td>

<td>

**REQUIRED**
</td>

<td>

```typescript
// Bad
let class = 'Mathematics';
let print  = function() {};

// Good 👍
let className = "Mathematics";
let printStudentName = function() {};
```
</td>


</tr>
</table>
<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br>

## 2. Styling & TypeScript

The good way to manage formatting, typing and runtime safety in Node.js TypeScript projects.

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

<td >

Let **Prettier** handle formatting rules such as semicolons, single quotes, trailing commas, print width, indentation (2 spaces), arrow function parentheses and line endings (LF). Team should not manually discuss formatting in code review.
</td>

<td>

**REQUIRED**
</td>

<td >

```typescript
{
  "semi": true,
  "singleQuote": true,
  "trailingComma": "all",
  "printWidth": 80,
  "tabWidth": 2,
  "useTabs": false,
  "arrowParens": "always",
  "endOfLine": "lf"
}
```
</td>
</tr>

<tr>
<td id='2.2'>

**2.2**
</td>

<td >

Function parameters must have explicit types.
</td>

<td>

**REQUIRED**
</td>

<td >

```typescript
// Bad
function calculateDiscount(price, discount) {
  return price * (discount / 100);
}

// Good 👍
function calculateDiscount(price: number, discount: number) {
  return price * (discount / 100);
}
```
</td>
</tr>

<tr>
<td id='2.3'>

**2.3**
</td>

<td>

Define explicit return types for exported/public functions and functions with complex return shapes. For simple local functions, explicit return types are optional when TypeScript inference is clear.
</td>

<td>

**REQUIRED** / **OPTIONAL**
</td>

<td>

```typescript
// Good 👍 simple local function can infer return type
function calculateDiscount(price: number, discount: number) {
  return price * (discount / 100);
}

// Good 👍 public/complex return should be explicit
export async function findResident(
  residentNo: string,
): Promise<ResidentDto | null> {
  return residentService.findByResidentNo(residentNo);
}

// Good 👍 complex union return should be explicit
function parsePage(value: unknown): number | null {
  if (typeof value !== 'string') return null;
  const page = Number(value);
  return Number.isInteger(page) && page > 0 ? page : null;
}
```
</td>
</tr>

<tr>
<td id='2.4'>

**2.4**
</td>

<td>

Use optional chaining `?.` and nullish coalescing `??` for nullable data. Avoid deeply nested defensive checks when language syntax can express it clearly.

</td>

<td>

**REQUIRED**
</td>

<td>

```typescript
// Bad
const city = user && user.address && user.address.city
  ? user.address.city
  : 'Unknown';

// Good 👍
const city = user?.address?.city ?? 'Unknown';
```
</td>
</tr>

<tr>
<td id='2.5'>

**2.5**
</td>

<td>

Use async/await instead of callbacks wherever possible
</td>

<td>

**RECOMMENDED**
</td>

<td>

```typescript
// Not good enough
function fetchData(callback: (data: any) => void): void {
  fetch('https://jsonplaceholder.typicode.com/todos/1')
    .then(response => response.json())
    .then(data => callback(data));
}

// Good 👍
async function fetchData(): Promise<any> {
  const response = await fetch('https://jsonplaceholder.typicode.com/todos/1');
  const data = await response.json();
  return data;
}
```
</td>
</tr>

<tr>

<tr>
<td id='2.6'>

**2.6**
</td>

<td>

Use interface for public object contracts that may be extended. Use type for unions, mapped types, utility types and function signatures.
</td>

<td>

**RECOMMENDED**
</td>

<td>

```typescript
// Good 👍 object contract
interface ResidentDto {
  residentNo: string;
  name: string;
}

// Good 👍 union type
type ResidentStatus = 'active' | 'inactive' | 'deleted';

// Good 👍 mapped/utility type
type ResidentPatch = Partial<Pick<ResidentDto, 'name'>>;
```
</td>
</tr>

<tr>
<td id='2.7'>

**2.7**
</td>

<td>

Avoid non-null assertion `!`. It disables TypeScript safety. Prefer validation, early return or throwing a clear error.
</td>

<td>

**RECOMMENDED**
</td>

<td>

```typescript
// Bad
const residentName = resident!.name;

// Good 👍
if (!resident) {
  throw new NotFoundError('Resident not found');
}

const residentName = resident.name;
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

```typescript
// Bad: repeats what the code does
// Check if resident is inactive
if (resident.status === 'inactive') return;

// Good 👍 explains business reason
// Inactive residents are kept for audit history and must not receive notifications.
if (resident.status === 'inactive') return;
```

</td>
</tr>

<tr>
<td id='3.2'>

**3.2**
</td>

<td>

Single-line comments should be short, clear and written like sentences.
</td>

<td>

**RECOMMENDED**
</td>


<td>

```typescript
// External partner requires this date format until their API v2 is released.
const formattedDate = format(date, 'yyyyMMdd');
```
</td>
</tr>

<tr>
<td id='3.3'>

**3.3**
</td>

<td>

Use multi-line comments only for complex business logic, temporary migration notes or non-obvious technical constraints.
</td>

<td>

**RECOMMENDED**
</td>

<td>

```typescript
/*
 * This migration must keep old resident numbers because external invoices
 * still reference them. Do not regenerate residentNo here.
 */
await migrateResidentContracts();
```
</td>
</tr>

<tr>
<td id='3.4'>

**3.4**
</td>

<td>Use JSDoc for exported utilities, public APIs or functions with important business rules. Do not add JSDoc to every small private function if the function name is already clear.
</td>

<td>

**REQUIRED**
</td>

<td>

```typescript
/**
 * Calculates the final billing amount after applying tax and discount rules.
 * This function must match the invoice specification used by the accounting team.
 */
export function calculateBillingAmount(input: BillingInput): BillingAmount {
  // ...
}
```

</td>
</tr>

<tr>
<td id='3.5'>

**3.5**
</td>

<td>

TODO/FIXME comments should include enough context to be actionable. If possible, include a ticket number or owner.
</td>

<td>

**RECOMMENDED**
</td>

<td>

```typescript
// Bad
// TODO: fix this

// Good 👍
// TODO(BWV-1234): Remove this fallback after partner API v2 is fully migrated.
const companyCode = input.companyCode ?? legacyCompanyCode;
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

Prefer native JavaScript/TypeScript APIs before adding utility libraries. Do not add lodash just to use basic `map`, `filter`, `find`, `some`, `every` or optional chaining. Use utility libraries only when they make the code clearly simpler or safer.
</td>

<td>

**REQUIRED**
</td>

<td >

```typescript
// Bad: unnecessary dependency for simple filtering
import { filter } from 'lodash';
const expensiveProducts = filter(products, (product) => product.price > 100);

// Good 👍
const expensiveProducts = products.filter((product) => product.price > 100);

// Acceptable 👍 when it improves readability
import groupBy from 'lodash/groupBy';
const residentsByCompany = groupBy(residents, 'companyNo');
```
</td>
</tr>

<tr>
<td id='4.2'>

**4.2**
</td>

<td>

Enable `@typescript-eslint/no-unused-vars`. Unused variables usually mean unfinished code, wrong refactor or wrong logic.
</td>

<td>

**REQUIRED**
</td>

<td>

```typescript
// Bad
const limit = Number(req.query.limit);
const page = Number(req.query.page);
return { limit };

// Good 👍
const limit = Number(req.query.limit);
const page = Number(req.query.page);
return { page, limit };
```
</td>
</tr>

<tr>
<td id='4.3'>

**4.3**
</td>

<td>

Do not use console in application code (e.g., APIs, front-end applications, background jobs, and batch processes). Use the project logger instead. Utility and development scripts (e.g., migrations and seed scripts) may use console where appropriate.
</td>

<td>

**REQUIRED**
</td>

<td>

```typescript
// Bad
console.log('Resident created', resident);

// Good 👍
logger.info({ residentNo: resident.residentNo }, 'Resident created');

// Good: utility script (e.g., migration or seed)
console.log('Resident seed completed');
```
</td>
</tr>

<tr>
<td id='4.4'>

**4.4**
</td>

<td>

Do not commit `debugger` statements.
</td>

<td>

**REQUIRED**
</td>

<td>

```typescript
// Bad
function isTruthy(value: unknown) {
  debugger;
  return Boolean(value);
}

// Good 👍
function isTruthy(value: unknown) {
  return Boolean(value);
}
```
</td>
</tr>

<tr>
<td id='4.5'>

**4.5**
</td>

<td>

Enable `no-var` and `prefer-const`. Use `const` unless reassignment is required.
</td>

<td>

**REQUIRED**
</td>

<td>

```typescript
// Bad
var name = 'John';
let limit = 50;

// Good 👍
const name = 'John';
const limit = 50;
```
</td>
</tr>

<tr>
<td id='4.6'>

**4.6**
</td>

<td>

Sort imports automatically using `simple-import-sort` or an equivalent tool. Keep import order consistent without manual effort.
</td>

<td>

**REQUIRED**
</td>

<td>

```typescript
// Bad
import b from 'bar.js';
import a from 'baz.js';

// Good 👍
import a from 'foo.js';
import b from 'bar.js';
```
</td>
</tr>

<tr>
<td id='4.7'>

**4.7**
</td>

<td>

Avoid abusing `any`. Prefer specific types or `unknown`. `any` is acceptable only for exceptional cases, such as legacy untyped libraries, with a clear comment.
</td>

<td>

**RECOMMENDED**
</td>

<td>

```typescript
// Bad
const age: any = 'seventeen';

// Good 👍 - specific type
const age: number = 17;

// Good 👍 - 'unknown' when type is uncertain, narrow before use
function parsePayload(raw: unknown) { ... }

// Acceptable with reason 👍
// eslint-disable-next-line @typescript-eslint/no-explicit-any -- legacy SDK has no type definitions
const legacyClient: any = require('legacy-untyped-sdk');
```
</td>

<tr>
<td id='4.8'>

**4.8**
</td>

<td>

**Importing specific functions**
</td>

<td>

**RECOMMENDED**
</td>

<td>

```typescript
// This way can help reduce the overall size of your bundle

import * as _ from 'lodash'; // <-- Bad

import { get , set } from 'lodash' // <-- Good 👍

import get from 'lodash/get' // <-- Best way but may not always be practical 👍
```
</td>
</tr>

<tr>
<td id='4.9'>

**4.9**
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
<td id='4.10'>

**4.10**
</td>

<td>

Enable `@typescript-eslint/no-floating-promises`. Do not start async work without handling errors.
</td>

<td>

**REQUIRED**
</td>

<td>

```typescript
// Bad
sendWelcomeEmail(user.email);

// Good 👍
await sendWelcomeEmail(user.email);

// Good 👍 explicitly ignore only with error handling
void sendAuditLog(event).catch((error) => {
  logger.error({ error }, 'Failed to send audit log');
});
```
</td>
</tr>

</table>

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br>

## 5. Security 

<table>
<tr>
<th>No</th>
<th>Rule</th>
<th>Priority</th>
<th>Example</th>
</tr>

<tr>
<td id='5.1'>

**5.1**
</td>

<td>

Use parameterized queries or ORM query builders that bind parameters safely. Never concatenate user input directly into SQL.
</td>

<td>

**REQUIRED**
</td>

<td >

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
</td>
</tr>

<tr>
<td id='5.2'>

**5.2**
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
- **Security Monitoring**: Look for any vulnerabilities in CVE databases and security alerts. Use [Snyk](https://security.snyk.io/vuln/npm) to check for vulnerabilities. Avoid libraries with unresolved or critical vulnerabilities.
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
<td id='5.3'>

**5.3**
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

**RECOMMENDED**
</td>

<td>

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
</td>
</tr>

<tr>
<td id='5.5'>

**5.5**
</td>

<td>

Use context-aware output encoding to prevent XSS. Do not manually insert untrusted input into HTML, JavaScript, CSS or URLs. When rendering user-provided rich text, sanitize it with an approved sanitizer.
</td>

<td>

**REQUIRED**
</td>

<td>

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
</td>
</tr>

<tr>
<td id='5.6'>

**5.6**
</td>

<td>

Avoid using direct user input for file paths, redirects, outbound URLs or shell commands. Use IDs, allowlists and safe mapping functions.
</td>

<td>

**REQUIRED**
</td>

<td>

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
</td>
</tr>

<tr>
<td id='5.7'>

**5.7**
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
<td id='5.8'>

**5.8**
</td>

<td>

Hash passwords. Do not encrypt passwords with reversible encryption. Use a strong password hashing algorithm such as Argon2id, bcrypt or PBKDF2 depending on project requirements.
</td>

<td>

**REQUIRED**
</td>

<td>

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
</td>
</tr>
</table>

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br>

## 6. Implement Lint

