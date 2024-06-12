
# Python Coding Rules
## Table of Contents
[**Common** ](#common)
<br>

[**1. Naming** ](#1-naming)
- [1.1 Name of files, functions, and variables use convention SnakeCase](#1.1)
- [1.2 Name of classes, enums use convention PascalCase](#1.2)
- [1.3 Avoid using overly long variable names](#1.3)
- [1.4 Name of constants use UPPER_CASE_UNDERSCORE format](#1.4)
- [1.5 Use meaningful variable names](#1.5)
- [1.6 DON’T use unclear abbreviations in variable names](#1.6)
- [1.7 Starting a boolean variable or property with a question words like can, is, should...](#1.7)
- [1.8 Avoid using variable names that are identical to keywords in the programming language or libraries](#1.8)
- [1.9 Always use self for the first argument to instance methods](#1.9)
  <br>

[**2. Styling** ](#2-styling)
- [2.1 Use 4 spaces per indentation level](#2.1)
- [2.2 Maximum Line Length](#2.2)
- [2.3 Blank Lines](#2.3)
- [2.4 Use single quotes for string literals unless you need to include a single quote within the string](#2.4)
- [2.5 Use type annotations whenever possible to help with code readability and maintainability](#2.5)
- [2.6 Use null checks and defensive programming techniques to avoid errors caused by undefined variables](#2.6)
- [2.7 Import rules](#2.7)
- [2.8 Whitespace in Expressions and Statements](#2.8)
- [2.9 Compound statements (multiple statements on the same line) are generally discouraged](#2.9)

[**3. Comment** ](#3-comment)
- [3.1 Single-line comment](#3.1)  
- [3.2 Multi-line comments](#3.2)  
- [3.3 Docstring comments](#3.3) 
- [3.4 Use english for comments](#3.4) 

[**4. Usage** ](#4-usage)
- [4.1 We should setup some config to ignore check some unnecessary files](#4.1) 
- [4.2 We should setup sort import packages](#4.2) 
- [4.3 In addition to the rules available in flake8, we can add some other rules that are not in flake8](#4.3) 

[**5. Security** ](#5-security)

[**6. Implement Lint** ](#6-implement-lint)
- [Step 1: Install package](#step-1)
- [Step 2: Create .flake8](#step-2)
- [Step 3: Create pyproject.toml](#step-3)
- [Step 4: Create requirements-dev.txt](#step-4)
- [Step 5: Run lint, format](#step-5)

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>

## Common 
- Always check wiki on redmine
- Always prioritize the coding rules of the project, follow the conventions of your project
- The following coding rules have been applied in some projects, depending on the project's style, the leader will select and apply them differently
 
<br>

## 1. Naming

<table>
<tr>
<td id='1.1'>

**1.1**
</td>

<td>

Name of files, functions, and variables use convention **SnakeCase**</td>
<td >

```py
# Bad
def createUser():
    pass

# Good 👍
def create_user():
    pass

# Bad
userName = 'polyhedra'

# Good 👍
user_name = 'polyhedra'
```

</td>
</tr>
<tr>
<td id='1.2'>

**1.2**
</td>
<td>

Name of classes, enums use convention **PascalCase**
</td>
<td>

```py
# Bad
class user:
    def __init__(self, user_name: str):
        self.user_name = user_name

# Good 👍
class User:
    def __init__(self, user_name: str):
        self.user_name = user_name

# Bad
from enum import Enum

class color(Enum):
    RED = 1
    GREEN = 2
    BLUE = 3

# Good 👍
from enum import Enum

class Color(Enum):
    RED = 1
    GREEN = 2
    BLUE = 3
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

```py
# Bad
this_is_a_variable_that_contains_the_user_name_of_the_user = 'polyhedra'

# Good 👍
user_name = 'polyhedra'
```

</td>
</tr>
<tr>
<td id='1.4'>

**1.4**
</td>
<td>

Name of constants use **UPPER_CASE_UNDERSCORE** format </td>
<td>

```py
# Bad
bucket_import_name = 'bucket_name'
bucketImportName = 'bucket_name'

# Good 👍
BUCKET_IMPORT_NAME = 'bucket_name'
```

</td>
<tr>
<td id='1.5'>

**1.5**
</td>
<td>

Use meaningful variable names</td>
<td>

```py
# Bad
x = 'polyhedra'
y = 18
z = '123456789'

# Good 👍
user_name = 'polyhedra'
age = 18
phone_number = '123456789'
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

```py
# Bad
usn = 'polyhedra'

# Good 👍
user_name = 'polyhedra'
```

</td>
</tr>
<tr>
<td id='1.7'>

**1.7**
</td>
<td>

Starting a boolean variable or property with a question words like *can, is, should*,... .
</td>
<td>

```py
is_connected = True
should_confirm = True
can_resize = True
```

</td>
</tr>
<tr>
<td id='1.8'>

**1.8**
</td>
<td> 

**Avoid** using variable names that are identical to keywords in the programming language or libraries
</td>
<td>

```py
# Bad
class = 'Color'

# Good 👍
class_name = 'Color'

# Bad
import math

math = 10
print(math)

# Good 👍
import math

math_value = 10
print(math_value)
```
</td>
</tr>

<tr>
<td id='1.9'>

**1.9**
</td>
<td> 

Always use **self** for the first argument to instance methods
</td>
<td>

```py
class User:
    def __init__(self, user_name: str, age: int):
        self.user_name = user_name
        self.age = age

    def get_user_information(self, user_id: int) -> str:
        return f'user_name: {self.user_name}, age: {self.age}, user_id: {user_id}'

user = User('polyhedra', 18)
print(user.get_user_information(1))
```
</td>
</tr>

</table>
<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br>

## 2. Styling

<table>
<tr>
<td id='2.1'>

**2.1**
</td>
<td >

Use 4 spaces per indentation level
</td>
<td >

```py
# Bad
def fibonacci(num: int) -> int:
  if num <= 0:
    return 0
  elif num == 1:
    return 1
  else:
    return fibonacci(num - 1) + fibonacci(num - 2)

print(fibonacci(5))

# Good👍
def fibonacci(num: int) -> int:
    if num <= 0:
        return 0
    elif num == 1:
        return 1
    else:
        return fibonacci(num - 1) + fibonacci(num - 2)

print(fibonacci(5))
```
</td>
</tr>

<tr>
<td id='2.2'>

**2.2**
</td>
<td>

**Limit all lines to a maximum of 79 characters**<br />
When the line exceeds column limit, it must be wrapped by follow conventions:
-	Break after a comma.
-	Break before an operator.
<td>

```py
# Bad
income = (gross_wages + taxable_interest + (dividends - qualified_dividends) - ira_deduction - student_loan_interest,)
income = (gross_wages +
          taxable_interest +
          (dividends - qualified_dividends) -
          ira_deduction -
          student_loan_interest,)


# Good 👍
income = (gross_wages
          + taxable_interest
          + (dividends - qualified_dividends) # Break before an operator
          - ira_deduction
          - student_loan_interest, # Break after a comma
          )
```
</td>
</tr>

<tr>
<td id='2.3'>

**2.3**
</td>
<td>

-	Surround top-level function and class definitions with two blank lines.
- Method definitions inside a class are surrounded by a single blank line.

</td>
<td>

```py
class MyClass:
    def __init__(self):
        self.value = 10

    def method_one(self):
        pass

    def method_two(self):
        pass


def example_function():
    pass


class MyClass2:
    def __init__(self):
        self.value = 10

    def method_one(self):
        pass

    def method_two(self):
        pass
```
</td>
</tr>

<tr>
<td id='2.4'>

**2.4**
</td>
<td>
Use single quotes for string literals unless you need to include a single quote within the string

</td>
<td>

```py
# Bad
message = "Hello polyhedra"

# Good👍
message = 'Hello polyhedra'
```
</td>
</tr>

<tr>

<tr>
<td id='2.5'>

**2.5**
</td>
<td>
Use type annotations whenever possible to help with code readability and maintainability
</td>
<td>

```py
# Bad
from typing import List

def sum_list(numbers):
    return sum(numbers)

result = sum_list([1, 2, 3, 4, 5])
print(result)

# Good 👍
from typing import List

def sum_list(numbers: List[int]) -> int:
    return sum(numbers)

result = sum_list([1, 2, 3, 4, 5])
print(result)
```
</td>
</tr>

<tr>
<td id='2.6'>

**2.6**
</td>
<td>
Use null checks and defensive programming techniques to avoid errors caused by undefined variables 
</td>
<td>

```py
# Bad
print(user.address.city)

# Good 👍
if user and user.get('address') and user.get('address').get('city'):
    print(user.get('address').get('city'))
else:
    print('City not found')

# Good 👍
city = getattr(getattr(user, 'address', {}), 'city', 'City not found')
print(city)

# Good 👍
from pydash import get

city = get(user, 'address.city')
print(city if city else 'City not found')
```
</td>
</tr>

<tr>
<td id='2.7'>

**2.7**
</td>
<td>

-	Imports should usually be on separate lines.
- Imports are always put at the top of the file, just after any module comments and docstrings, and before module globals and constants.
- Imports should be grouped in the following order: <br/>
    1/ Standard library imports <br/>
    2/ Related third party imports <br/>
    3/ Local application/library specific imports <br/>
- Wildcard imports (from module import *) should be avoided

</td>
<td>

```py
# Bad
import sys, os, math
from subprocess import *

# Good 👍
'''
Module example
'''

# Standard library imports
import os
import sys
import math
from subprocess import Popen, PIPE # It’s okay to say this though

# Related third party imports
import numpy
import pandas
from pydash import get

# Local application/library specific imports
from mymodule import MyClass
from mymodule.utils import helper_function

# Variable declarations
DEBUG = True
MAX_ITERATIONS = 1000
```
</td>
</tr>

<tr>
<td id='2.8'>

**2.8**
</td>
<td>

**Avoid extraneous whitespace in the following situations:**
-	Immediately inside parentheses, brackets or braces.
- Between a trailing comma and a following close parenthesis.
- Immediately before a comma or colon.
- Immediately before the open parenthesis that starts the argument list of a function call.
- Immediately before the open parenthesis that starts an indexing or slicing.
- Function annotations should use the normal rules for colons and always have spaces around the **->** arrow if present.

</td>
<td>

```py
# Bad
spam( ham[ 1 ], { eggs: 2 } )
foo = (0, )

if x == 4 :
    print(x , y)
    x , y = y , x

spam (1)
dct ['key'] = lst [index]
def munge(input:AnyStr)->PosInt: ...

# Good 👍
spam(ham[1], {eggs: 2})
foo = (0,)

if x == 4:
    print(x, y)
    x, y = y, x

spam(1)
dct['key'] = lst[index]
def munge(input: AnyStr) -> PosInt: ...
```
</td>
</tr>

<tr>
<td id='2.9'>

**2.9**
</td>
<td>

Compound statements (multiple statements on the same line) are generally discouraged
</td>
<td>

```py
# Bad
if foo == 'blah': do_blah_thing()
else: do_non_blah_thing()

for x in lst: total += x

while t < 10: t = delay()

try: something()
finally: cleanup()

do_one(); do_two(); do_three()

# Good 👍
if foo == 'blah':
    do_blah_thing()
else
    do_non_blah_thing()

for x in lst:
    total += x

while t < 10:
    t = delay()

try:
    something()
finally:
    cleanup()

do_one()
do_two()
do_three()
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

**Single-line comment**<br />
Format comments like sentences, begin with 1 whitespace and capitalize the first word.

</td>

<td>

```py
# In case no item in list, we do nothing
if not has_items:
    return False
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

```py
# This is a class User
# This class is used to define properties and methods of user
# ...
class User:
    def __init__(self, user_name: str, age: int):
        self.user_name = user_name
        self.age = age

    def get_user_information(self) -> str:
        return f'user_name: {self.user_name}, age: {self.age}'
```
</td>
</tr>

<tr>
<td id='3.3'>

**3.3**
</td>
<td>

**Docstring comments**<br />
Must be placed inside function or class.

</td>
<td>

```py
class User:
    '''
    A class to represent a user.

    Attributes:
    ----------
    user_name : str
        The name of the user.
    age : int
        The age of the user.

    Methods:
    -------
    get_user_information(user_id: int) -> str
        Returns a formatted string with the user's information.
    '''

    def __init__(self, user_name: str, age: int):
        self.user_name = user_name
        self.age = age

    def get_user_information(self, user_id: int) -> str:
        return f'user_name: {self.user_name}, age: {self.age}, user_id: {user_id}'


def add(a: int, b: int) -> int:
    '''
    Adds two numbers together.
    
    Args:
        a (int): The first number to add.
        b (int): The second number to add.
    
    Returns:
        int: The sum of a and b.
    '''
    return a + b
```

</td>
</tr>

<tr>
<td id='3.4'>

**3.4**
</td>
<td>

**Use** english for comments

</td>
<td>

```py
# Bad
customers = []  # Mảng chứa các khách hàng

# Good 👍
customers = []  # Array of customers
```

</td>
</tr>
</table>
<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br>

## 4. Usage

Should use one or more tools to support format coding files such as flake8, black.

<table>

<tr>
<td id='4.1'>

**4.1**

</td>
<td>

We should setup some config to ignore check and format some unnecessary files

</td>
<td >

```py
# Example .flake8
[flake8]
exclude = env,test,__pycache__

# Example pyproject.toml
[tool.black]
exclude = '''
/(
    \.pytest_cache
  | env
)/
'''
```
</td>
</tr>

<tr>
<td id='4.2'>

**4.2**

</td>
<td>

We should setup sort import packages

</td>
<td>

```py
# Example pyproject.toml
[tool.isort]
profile = 'black'
```
</td>
</tr>

<tr>
<td id='4.3'>

**4.3**

</td>
<td>

In addition to the rules available in flake8, we can add some other rules that are not in flake8

</td>
<td>

```py
# Example requirements-dev.txt
flake8-bandit
flake8-bugbear
flake8-builtins
flake8-comprehensions
flake8-eradicate
flake8-implicit-str-concat
flake8-print
isort
pep8-naming
```
</td>
</tr>
</table>

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br>

## 5. Security 

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>
<br>

## 6. Implement Lint

Here is an example of how to implement flake8 into a Python project. <br>
Ref: https://flake8.pycqa.org/en/latest/ <br>

Here is an example of how to implement black into a Python project. <br>
Ref: https://black.readthedocs.io/en/stable/getting_started.html <br>

#### Step 1

**Install package** <br/>
```py
pip install flake8
pip install black
```

#### Step 2

**Create .flake8** <br/>
```py
[flake8]
max-line-length = 79
exclude = env,test,__pycache__,.git
max-complexity = 20
extend-ignore = E711,A002,A003,B010,E713,E714,E722,E731
```

#### Step 3

**Create pyproject.toml** <br/>
```py
[build-system]
requires = ["setuptools", "wheel"]
build-backend = "setuptools.build_meta"

[tool.black]
line-length = 79
skip-string-normalization = true
target-version = ['py38', 'py39']
exclude = '''
/(
    \.pytest_cache
  | env
)/
'''

[tool.isort]
profile = 'black'
skip = ['env', '.pytest_cache']
```

#### Step 4

**Create requirements-dev.txt** <br/>
```py
black
flake8
flake8-implicit-str-concat
flake8-print
isort
pep8-naming
```

#### Step 5

**Run lint, format**<br/>
```py
Run format: black .
Run lint: flake8
```

<br>
Ref rule here: https://www.flake8rules.com/
<br>

<p align="right">(<a href="#table-of-contents">back to top</a>)</p>