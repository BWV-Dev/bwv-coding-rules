
# Flutter Coding Rules

## Definitions

<table>
    <tr>
        <td> <b>UpperCamelCase</b></td>
        <td> names capitalize the first letter of each word, including the first. etc: <b>MyClass</b> </td>
    </tr>
    <tr>
        <td> <b>lowerCamelCase</b> </td>
        <td> names capitalize the first letter of each word, except the first which is always lowercase, even if it’s an acronym. etc: <b>studentList</b> </td>
    </tr>
    <tr>
        <td> <b>lowercase_with_underscores</b> </td>
        <td> names use only lowercase letters, even for acronyms, and separate words with _. etc: <b>home_screen.dart</b> </td>
    </tr>

</table>

## 1. Naming rules 

<table>
<tr>
<td>

Name of files, packages, directories, import prefixes use [lowercase_with_underscores](#definitions) format. 

</td>
<td>

```dart
my_package
└─ lib
   └─ file_system.dart
   └─ slider_menu.dart

import 'dart:math' as math;
```

</td>
</tr>
<tr>
<td>

Name of classes, extensions, enum types use [UpperCamelCase](#definitions) format. 

</td>
<td>

```dart
class SliderMenu { ... }

extension MyFancyList<T> on List<T> { ... }

enum MyType { ... } 
```

</td>
</tr>
<tr>
<td>

Name of constant, variables, enum variables use [lowerCamelCase](#definitions) format.

</td>
<td>

```dart
const defaultTimeout = 1000;

final List<Student> studentList = [];

enum MyType { 
    typeOne,
    typeTwo
 } 
```

</td>
</tr>
<tr>
<td>Use prefix `_` for private identifiers (classes, variable,...) </td>
<td>

```dart
class _PrivateClass {...}

final _privateVariable = true;
```

- Use `_, __, etc.` for unused callback parameters
```dart
futureOfVoid.then((_,__) {
  print('done.');
});
```

</td>
</tr>
<tr>
<td>DON’T use prefix letters.</td>
<td>

```dart
const defaultTimeout = 10; //good
const kDefaultTimeout = 10;  //bad
```

</td>
</tr>
</tr>
<td>
Capitalize acronyms and abbreviations longer than two letters like words.
</td>
<td>

```dart
class HttpConnection {} //good
class HTTPConnection {} //bad
```

</td>
</tr>
</table>