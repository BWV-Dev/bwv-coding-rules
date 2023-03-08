
# Flutter Coding Rules

## Definitions
- <u>**UpperCamelCase**</u> names capitalize the first letter of each word, including the first. etc: `MyClass`

- <u>**lowerCamelCase**</u> names capitalize the first letter of each word, except the first which is always lowercase, even if it’s an acronym. etc: `studentList`

- <u>**lowercase_with_underscores**</u> names use only lowercase letters, even for acronyms, and separate words with _. etc: `home_screen.dart`

## 1. Naming rules 

- Name of files, packages, directories, import prefixes use [lowercase_with_underscores](#definitions) format.
```dart
my_package
└─ lib
   └─ file_system.dart
   └─ slider_menu.dart

import 'dart:math' as math;
```

- Name of classes, extensions, enum types use [UpperCamelCase](#definitions) format. 

```dart
class SliderMenu { ... }

extension MyFancyList<T> on List<T> { ... }

enum MyType { ... } 
```

- Name of constant, variables, enum variables use [lowerCamelCase](#definitions) format.

```dart
const defaultTimeout = 1000;

final List<Student> studentList = [];

enum MyType { 
    typeOne,
    typeTwo
 } 
```

- Use prefix `_` for private identifiers (classes, variable,...) 
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

- DON’T use prefix letters.<br>
```dart
const defaultTimeout = 10; //good
const kDefaultTimeout = 10;  //bad
```

- DO capitalize acronyms and abbreviations longer than two letters like words.
```dart
class HttpConnection {} //good
class HTTPConnection {} //bad
```

