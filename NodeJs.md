
# NodeJs Coding Rules & Security (BWV)
## Table of Contents
[**Common** ](#common)
<br>

[**1. Naming** ](#1-naming)
- [1.1 Typescript normally use convention camelCase for variable](#1.1)
- [1.2 Use meaningful variable names](#1.2)
- [1.3 Avoid using overly long variable names](#1.3)
- [1.4 Do not start variable names with an underscore](#1.4)
- [1.5 Can use prefix variable names to indicate the data type](#1.5)
- [1.6 DON’T use unclear abbreviations in variable names](#1.6)
- [1.7 Should use UPPER_CASE and _ for CONSTANT variable](#1.7)
- [1.8 Starting a boolean variable or property with a question words like can, is, should...](#1.8)
- [1.9 Should use let or const for variable instead var](#1.9)
- [1.10 Avoid using variable names that are identical to keywords in the programming language or libraries](#1.10)
- [1.11 TypeScript conventions, use PascalCase for class names, data types, and enumerations (enums)](#1.11)
  <br>

[**2. Styling** ](#2-styling)
- [2.1 Use single quotes for string literals unless you need to include a single quote within the string](#2.1)
- [2.2 Use type annotations whenever possible to help with code readability and maintainability](#2.2)
- [2.3 Use tab with 2 spaces indentation](#2.3)
- [2.4 Use async/await instead of callbacks wherever possible](#2.4)
- [2.5 Use null checks and defensive programming techniques to avoid errors caused by undefined variables](#2.5)  
- [2.6 Should use interfaces to define object shapes](#2.6)  
<br>

[**3. Comment** ](#3-comment)
- [3.1 Single-line comment. Format comments like sentences and capitalize the first word](#3.1)  
- [3.2 Multi-line comments](#3.2)  
- [3.3 JSDoc comments](#3.3) 
<br>

[**4. Usage** ](#4-usage)
- [4.1 Should use library such as lodash to work with Object , Array and more to avoid some exception errors](#4.1) 
- [4.2 We should setup some config to ignore check some unnecessary files](#4.2) 
- [4.3 no-unused-vars](#4.3) 
- [4.4 no-console](#4.4) 
- [4.5 semi](#4.5) 
- [4.6 no-debugger](#4.6) 
- [4.7 no-var](#4.7) 
- [4.8 sort-import](#4.8) 
- [4.9 no-explicit-any](#4.9) 
- [4.10 Importing specific functions](#4.10) 

<br>

[**5. Security** ](#5-security)
- [5.1 Use parameterized queries](#5.1) 
- [5.2 Use libraries with a good security track record](#5.2) 
- [5.3 Implement rate limiting](#5.3) 
- [5.4 Use logging and monitoring](#5.4) 
- [5.5 Escape data HTML](#5.5) 
- [5.6 Avoid using input from user to handle files / redirect url etc...](#5.6) 
- [5.7 Should limit IP access to the database](#5.7) 
- [5.8 Should encrypt sensitive information if it is necessary to store them in a database](#5.8) 
<br>

[**6. Implement Lint** ](#6-implement-lint)

## Common 
- Always check wiki on redmine
- Always prioritize the coding rules of the project, follow the conventions of your project
- The following coding rules have been applied in some projects, depending on the project's style, the leader will select and apply them differently
 


## 1. Naming
The good way to name folder,class,type and variable in Typescript.

<table>
<tr>
<td id='1.1'>

**1.1**
</td>

<td>

Typescript normally use convention **camelCase** for variable</td>
<td >

```typescript
// Bad
let first_name = 'John';

// Good 👍
let firstName = 'John';
```

</td>
</tr>
<tr>
<td id='1.2'>

**1.2**
</td>
<td>

Use meaningful variable names
</td>
<td>

```typescript
// Bad
let a = 'John';
let b = 20;

// Good 👍
let firstName = 'John';
let age = 20;
```

</td>
</tr>
<tr>
<td id='1.3'>

**1.3**
</td>
<td>

Avoid using overly long variable names

</td>
<td>

```typescript
// Bad
let thisIsAVariableThatContainsTheFirstNameOfTheUser = 'John';

// Good 👍
let firstName = 'John';

```

</td>
</tr>
<tr>
<td id='1.4'>

**1.4**
</td>
<td>

Do not start variable names with an underscore </td>
<td>

```typescript
// Bad
let _firstName = 'John';

```

</td>
<tr>
<td id='1.5'>

**1.5**
</td>
<td>

Can use prefix variable names to indicate the data type</td>
<td>

```typescript
// Better can do
let strName = 'John';
let numValue = 10;
```

</td>
</tr>
<tr>
<td id='1.6'>

**1.6**
</td>
<td>

**DON’T** use unclear abbreviations in variable names</td>
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
<td id='1.7'>

**1.7**
</td>
<td>

Should use **UPPER_CASE** and **_** for CONSTANT variable
</td>
<td>

```typescript
// Bad
const bucketUpload = 'folder'

// Good 👍
const BUCKET_UPLOAD = 'folder';
```

</td>
</tr>
<tr>
<td id='1.8'>

**1.8**
</td>
<td> 

Starting a boolean variable or property with a question words like *can, is, should*,... .
</td>
<td>

```typescript
let isConnected = true;
let shouldConfirm = true;
let canResize = true;
```
</td>
</tr>
<tr>
<td id='1.9'>

**1.9**
</td>
<td>

Should use **let** or **const** for variable instead **var**</td>
<td>

```typescript
// Bad
var num = 10;
var PI = 3.14;

// Good 👍
let num = 10;
const PI = 3.14;
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

<tr>
<td id='1.11'>

**1.11**
</td>
<td>

TypeScript conventions, use PascalCase for class names, data types, and enumerations (enums)
</td>
<td>

```typescript
// Bad
class person {
  firstName: string;
}

enum color {
  Red,
}

// Good 👍
class Person {
  firstName: string;
}

enum Color {
  Red,
}
```
</td>
</tr>

</table>
<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br>

## 2. Styling

The good way to manage structure and environment

<table>
<tr>
<td id='2.1'>

**2.1**
</td>
<td >

Use single quotes for string literals unless you need to include a single quote within the string
</td>
<td >

```typescript
// Bad
const message = "Hello, world!";

// Good👍
const message = 'Hello, world!';

```
</td>
</tr>

<tr>
<td id='2.2'>

**2.2**
</td>
<td>

Use type annotations whenever possible to help with code readability and maintainability
<td>

```typescript
// Bad
function calculateDiscount(price, discountPercentage) {
  return price * (discountPercentage / 100);
}

// Good 👍
function calculateDiscount(price: number, discountPercentage: number): number {
  return price * (discountPercentage / 100);
}
```
</td>
</tr>

<tr>
<td id='2.3'>

**2.3**
</td>
<td>

Use tab with 2 spaces indentation

</td>
<td>

```typescript
// Bad
function multiplyNumbers(a: number, b: number): number {
    const result = a * b;
    return result;
}

// Good 👍
function multiplyNumbers(a: number, b: number): number {
  const result = a * b;
  return result;
}
```
</td>
</tr>

<tr>
<td id='2.4'>

**2.4**
</td>
<td>
Use async/await instead of callbacks wherever possible

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
<td id='2.5'>

**2.5**
</td>
<td>
Use null checks and defensive programming techniques to avoid errors caused by undefined variables
<br>
Can use Optional chaining syntax (available in Higher Node version ~ v14) or Lodash.Get()
</td>
<td>

```typescript
// Bad
console.log(user.address.city);

// Good 👍
if (user && user.address && user.address.city) {
  console.log(user.address.city);
} else {
  console.log("City not found.");
}
```
</td>
</tr>

<tr>
<td id='2.6'>

**2.6**
</td>
<td>

Should use interfaces to define object shapes.

</td>
<td>

```typescript
interface User {
  firstName: string;
  lastName: string;
  age: number;
}
```
</td>
</tr>
</table>
<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br>

## 3. Comment

Commenting code is an important aspect of software development as it helps other developers understand your code and makes it easier to maintain.

<table>
<tr>
<td id='3.1'>

**3.1**
</td>

<td>
Single-line comment. Format comments like sentences and capitalize the first word.
</td>

<td>

```typescript
// In case no item in list, we do nothing
if (!hasItems) return false;
```

</td>
</tr>

<tr>
<td id='3.2'>

**3.2**
</td>
<td>

Multi-line comments</td>
<td>

```typescript
/*
This is a multi-line comment.
It can be used to explain large sections of code.
*/
const age = 30;
```
</td>
</tr>

<tr>
<td id='3.3'>

**3.3**
</td>
<td>JSDoc comments</td>
<td>

```typescript
/**
* Adds two numbers together.
* @param {number} a - The first number to add.
* @param {number} b - The second number to add.
* @returns {number} - The sum of a and b.
*/
function add(a: number, b: number): number {
    return a + b;
}
```

</td>
</tr>
</table>
<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br>

## 4. Usage

Should use one or more tools to support format coding files such as Prettier, Eslint, Typescript-eslint.

<table>

<tr>
<td id='4.1'>

**4.1**
</td>
<td>
Should use library such as lodash to work with Object , Array and more to avoid some exception errors
</td>
<td >

```typescript
// Bad
const expensiveProducts = products.filter((product) => product.price > 100);

// Good 👍
import { filter } from "lodash/filter";

const expensiveProducts = _.filter(products, (product) => product.price > 100);

```
</td>
</tr>

<tr>
<td id='4.2'>

**4.2**
</td>
<td>
We should setup some config to ignore check some unnecessary files
</td>
<td>

```typescript
// Example .eslintignore
{
    "ignorePatterns": ["temp.js", "**/vendor/*.js"],
    "rules": {
        //...
    }
}
```
</td>
</tr>

<tr>
<td id='4.3'>

**4.3**
</td>
<td>

**no-unused-vars**
</br>

</td>
<td>

```typescript
// Disallow the use of console
let x = 1; // <-- Error , x does not use anywhere
let y = 2;
alert(y); 
```
</td>
</tr>

<tr>
<td id='4.4'>

**4.4**
</td>
<td>

**no-console**
</td>
<td>

```typescript
// This rule disallows the use of console.log statements in production code.

console.log('Log something...'); // <-- Error
```
</td>
</tr>

<tr>
<td id='4.5'>

**4.5**
</td>
<td>

**semi**
</td>
<td>

```typescript
// This rule enforces semi-colons at the end of statements.
var name = "ESLint" // <--- Error, required ';' at the end
var website = "eslint.org";
```
</td>
</tr>

<tr>
<td id='4.6'>

**4.6**
</td>
<td>

**no-debugger** 
</td>
<td>

```typescript
// This rule disallows the use of the debugger statement in production code.

function isTruthy(x) {
    debugger; // <-- Error line
    return Boolean(x);
}
```
</td>
</tr>

<tr>
<td id='4.7'>

**4.7**
</td>
<td>

**no-var**
</td>
<td>

```typescript
// This rule encourages the use of let or const instead of var.

var name = "name"; // <-- Error
let age = 10;
```
</td>
</tr>

<tr>
<td id='4.8'>

**4.8**
</td>
<td>

**sort-import**
</td>
<td>

```typescript
// This rule ensures that all import statements are sorted
// alphabetically and separated by an empty line.

// No error
import a from 'foo.js';
import b from 'bar.js';

// Error
import b from 'bar.js';
import a from 'baz.js';
```
</td>
</tr>

<tr>
<td id='4.9'>

**4.9**
</td>
<td>

**no-explicit-any**
</td>
<td>

```typescript
// This rule disallows the use of the any type, which can lead to unsafe code.

const age: any = 'seventeen'; // <-- Bad

const age: number = 17; // <-- Good 👍
```
</td>


<tr>
<td id='4.10'>

**4.10**
</td>
<td>

**Importing specific functions**
</td>
<td>

```typescript
// This way can help reduce the overall size of your bundle

import * as _ from 'lodash'; // <-- Bad

import { get , set } from 'lodash' // <-- Good 👍

import get from 'lodash/get' // <-- Best way but may not always be practical 👍
```
</td>
</table>

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br>

## 5. Security 

<table>

<tr>
<td id='5.1'>

**5.1**
</td>
<td>

**Use parameterized queries.**
Depend on your ORM framework, Always ensure that the method supports binding parameters.
(Prevent SQL injection attacks)
</td>
<td >

```typescript
// Bad
const query = `SELECT * FROM users WHERE id=${userId}`;

// Good 👍
const query = "SELECT * FROM users WHERE id=?";

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

```typescript
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

```typescript
// Bad example: no rate limiting
app.get("/search", (req, res) => {
  //perform search operation
});

// Good example: implementing rate limiting 👍
const rateLimit = require("express-rate-limit");
const searchLimiter = rateLimit({
  windowMs: 60 * 1000, //1 minute
  max: 10 //10 requests per minute
});
app.get("/search", searchLimiter, (req, res) => {
  // perform search operation
});
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

**Escape data HTML.**</br>
(XSS (Cross-site scripting) attack)</br>
Convert special character: </br>
  [&] ⇒ [\&amp;] </br>
  [<] ⇒ [\&lt;]</br>
  [>] ⇒ [\&gt;]</br>
  []] ⇒ [\&quot;]</br>
  [’] ⇒ [\&#39;]
</td>
<td>

```typescript
const ejs = require('ejs');
const escapeHtml = require('escape-html');

// Render the template
const html = ejs.render('<%= escapeHtml(data) %>', 
{ data: '<script>alert("XSS attack!")</script>' }
);

// Output: &lt;script&gt;alert(&quot;XSS attack!&quot;)&lt;/script&gt;
console.log(html); 
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

```typescript
// Bad
res.redirect(req.body.hiddenInputUrl);

// Bad
const dataFileDetail = fs.readFileSync(req.body.filePath);

// Good 👍
const redirectUrl = getUrlFromInputId(req.body.hiddenInputUrlId);
res.redirect(redirectUrl);
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

**Should** encrypt sensitive information if it is necessary to store them in a database.<br>
Such as using brypt to encrypt password.
</td>
<td>

```typescript
import bcrypt from 'bcrypt';

const SALT_ROUNDS = 10;

export const hashPassword = async (password: string): Promise<string> => {
  const salt = await bcrypt.genSalt(SALT_ROUNDS);
  return bcrypt.hash(password, salt);
};

```
</td>
</tr>
</table>

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br>

## 6. Implement Lint

Here is an example of how to implement Eslint into a Node.js TypeScript project. <br>
Ref: https://eslint.org/docs/latest/use/getting-started <br>

**Step 1: Install package**
```typescript
npm install eslint @typescript-eslint/parser @typescript-eslint/eslint-plugin --save-dev
npm install prettier eslint-config-prettier eslint-plugin-prettier --save-dev
npm install prettier eslint-plugin-simple-import-sort --save-dev
```

**Step 2: Create .eslintrc.json (or .js or .yml)**

```json
{
    "env": {
        "browser": true,
        "commonjs": true,
        "es2021": true
    },
    "extends": [
      "plugin:@typescript-eslint/recommended",
      "plugin:prettier/recommended",
      "prettier"
    ],
    "parser": "@typescript-eslint/parser",
    "parserOptions": {
        "ecmaVersion": "latest"
    },
    "plugins": [
      "@typescript-eslint",
      "prettier",
      "simple-import-sort"
    ],
    "rules": {
      "@typescript-eslint/no-unused-vars": "error",
      "@typescript-eslint/no-non-null-assertion": "error",
      "@typescript-eslint/no-explicit-any": "error",
      "no-console": "error",
      "simple-import-sort/imports": "error",
      "simple-import-sort/exports": "error",
      "no-duplicate-imports": "error"
    }
}
```

**Step 3: Create .prettierrc**

```json
{
  "arrowParens": "always",
  "trailingComma": "all",
  "tabWidth": 2,
  "singleQuote": true,
  "overrides": [{
    "files": "*.ts",
    "options": {
      "parser": "typescript"
    }
  }]
}

```

**Step 4: Add script (package.json)**

```json
"scripts": {
  "lint": "eslint . --ext .ts",
  "lint-and-fix": "eslint . --ext .ts --fix",
},
```

**Explain:** 
<br>

**lint** Command to check and show error. <br>
**lint-and-fix** Command to fix and replace some rules (**). Some rules are automatically fixable by this command. <br>
Ref here: https://eslint.org/docs/latest/rules/
<br>

**There are some rules that don't necessarily have to produce an error all the time. For example, in special situations where we need to use the 'any' type, we can disable these rules with the following command:**

```javascript
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
```
<p align="right">(<a href="#table-of-contents">back to top</a>)</p>