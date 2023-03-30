
# Flutter Coding Rules

## Table of Contents

[**1. Naming** ](#1-naming)
- [1.1 Name of files, packages, directories, import prefixes.](#1.1)
- [1.2 Name of classes, extensions, enum types.](#1.2)
- [1.3 Name of constant, variables, enum variables.](#1.3)
- [1.4 Use prefix _ for private identifiers (classes, variable,...).](#1.4)
- [1.5 Use _ or __ for unused callback parameters.](#1.5)
- [1.6 DON’T use prefix letters.](#1.6)
- [1.7 Capitalize acronyms and abbreviations longer than two letters like words.](#1.7)
- [1.8 Starting a boolean variable or property with a question words like can, is, should,..](#1.8)
- [1.9 PREFER naming a method as___() if it returns a different representation backed by the original object.](#1.9)
- [1.10 Note in naming any identifiers(variables, functions, classes,constants,...).](#1.10)
  <br>

[**2. Styling** ](#2-styling)
- [2.1 Prefer a maximum line length of 80 characters.](#2.1)
- [2.2 DO use mixin to define a mixin type.](#2.2)
- [2.3 Import packages by following order and sort each section by alphabetically.](#2.3)
- [2.4 Use curly braces for all flow control statements.](#2.4)
- [2.5 Keep functions short and focused.](#2.5)  
<br>

[**3. Comment** ](#3-comment)
- [3.1 Format comments like sentences and capitalize the first word.](#3.1)  
- [3.2 Use /// to document members and types.](#3.2)  
- [3.3 Can use markdown on comments.](#3.3)  
- [3.4 USE english for comments.](#3.4) 
<br>

[**4. Usage** ](#4-usage)

- [4.1 Avoid using as instead, use is operator.](#4.1) 
- [4.2 Use library and part of to describe libraries.](#4.2) 
- [4.3 DON’T import libraries that are inside the src directory of another package.](#4.3) 
- [4.4 DON’T allow an import path to reach into or out of lib.](#4.4) 
- [4.5 Prefer using relative imports in local package.](#4.5) 
- [4.6 AVOID using late variables if you need to check whether they are initialized.](#4.6) 
- [4.7 DON'T use the same name for properties and local variables.](#4.7) 
- [4.8 PREFER using interpolation to compose strings and values.](#4.8) 
- [4.9 DO use whereType() to filter a collection by type.](#4.9) 
- [4.10 AVOID using cast().](#4.10) 
- [4.11 Use final and var.](#4.11) 
- [4.12 CONSIDER using => for simple members.](#4.12) 
- [4.13 DON’T use new.](#4.13) 
- [4.14 Method cascades are a better solution for chaining method calls.](#4.14) 
- [4.15 AVOID writing incomplete generic types.](#4.15) 
- [4.16 AVOID using dynamic unless you want to disable static checking.](#4.16)
- [4.17 DO override hashCode if you override ==.](#4.17)
- [4.18 Use class to define your constants.](#4.18)
- [4.19 Avoid large functions and widgets.](#4.19)
- [4.20 Exit when possible.](#4.20)
- [4.21 Use ?? and ?. operators.](#4.21)
- [4.22 Use spread collections.](#4.22)
- [4.23 Use ListView.builder() for a long list instead of ListView().](#4.23)
- [4.24 Use asserts to detect contract violations and verify invariants.](#4.24)
- [4.25 USE SizedBox for empty widget.](#4.25)
- [4.26 Create controller to control widget.](#4.26)
- [4.27 Avoid interleaving multiple concepts together.](#4.27)
- [4.28 Be explicit about dispose() and the object lifecycle.](#4.28)
  

<p align="right">(<a href="#flutter-coding-rules">back to top</a>)</p>

## 1. Naming

<table>
<tr id="1.1">

<td width="5%">

**1.1**
</td>
<td width="50%">

Name of files, packages, directories, import prefixes use **lowercase_with_underscores**(use only lowercase letters, even for acronyms, and separate words with _ ) format. </td>
<td width="45%">

```dart
my_package
└─ lib
   └─ file_system.dart
   └─ slider_menu.dart

import 'dart:math' as math;
```

</td>
</tr>
<tr id="1.2">
<td>

**1.2**
</td>
<td>

Name of classes, extensions, enum types use **UpperCamelCase**(capitalize the first letter of each word, including the first) format.
</td>
<td>

```dart
class SliderMenu { ... }

extension MyFancyList<T> on List<T> { ... }

enum MyType { ... } 
```

</td>
</tr>
<tr id="1.3">
<td>

**1.3**
</td>
<td>

Name of constant, variables, enum variables use **lowerCamelCase**(capitalize the first letter of each word, except the first which is always lowercase, even if it’s an acronym) format.

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

<tr id="1.4">
<td>

#### 1.4
</td>
<td>

Use prefix **_** for private identifiers (classes, variable,...). </td>
<td>

```dart
class _PrivateClass {...}

final _privateVariable = true;
```

</td>
<tr id="1.5">
<td>

#### 1.5
</td>
<td>

Use **_** or **__**  for unused callback parameters.</td>
<td>

```dart
futureOfVoid.then((_,__) {
  print('done.');
});
```

</td>
</tr>

<tr id="1.6">
<td>

#### 1.6
</td>
<td>

**DON’T** use prefix letters.</td>
<td>

```dart
const defaultTimeout = 10; //good
const kDefaultTimeout = 10;  //bad
```

</td>
</tr>

<tr id="1.7">
<td>

#### 1.7
</td>
<td>

**Capitalize** acronyms and abbreviations longer than two letters like words.
</td>
<td>

```dart
class HttpConnection {} //good
class HTTPConnection {} //bad
```

</td>
</tr>

<tr id="1.8">
<td>

#### 1.8
</td>
<td> 

Starting a boolean variable or property with a question words like *can, is, should*,... .
</td>
<td>

```dart
final isConnected = true;
final shouldConfirm = true;
final canResize = true;
```
</td>
</tr>

<tr id="1.9">
<td>

#### 1.9
</td>
<td>

**PREFER** naming a method **as___()** if it returns a different representation backed by the original object.</td>
<td>

```dart
var map = table.asMap();
var list = bytes.asFloat32List();
```
</td>
</tr>

<tr id="1.10">
<td>

#### 1.10
</td>
<td>

When naming any identifiers(variables, functions, classes,constants,...), **MUST** use names that accurately describe the data, function they contain. Avoid using generic names like "value" or "temp" as they do not convey any meaningful information.
</td>
<td>

```dart
//good
String userName;
validateInput() {}
const double pi = 3.14;
class UserModel {}

//bad
String value;
doSomething() {}
const double constant = 3.14;
class Data {}
```
</td>
</tr>

</table>

<p align="right">(<a href="#flutter-coding-rules">back to top</a>)</p>

## 2. Styling

<table>
<tr id="2.1">
<td width="5%" >

#### 2.1
</td>
<td width="50%">

**Prefer a maximum line length of 80 characters**<br>
Aim for a maximum line length of roughly 80 characters, but prefer going over if breaking the line would make it less readable, or if it would make the line less consistent with other nearby lines. 
</td>
<td width="45%">

```dart
//bad
final names = a.map(element => element.name).toList().sort()......;
//good
List<String> names = a.map(element => element.name);
names.sort();
names....;

```
</td>
</tr>

<tr id="2.2">
<td>

#### 2.2
</td>
<td>

**DO** use *mixin* to define a mixin type.</td>
<td>

```dart
mixin ClickableMixin  {}
```
</td>
</tr>

<tr id="2.3">
<td>

#### 2.3
</td>
<td>

Import packages by following order and sort each section by alphabetically.

```dart
dart: 
package:
relative imports (../foo.dart)
```
</td>
<td>

```dart
// good
import 'dart:a';
import 'dart:b';

import 'package:a/a.dart';
import 'package:b/b.dart';

import 'a.dart';
import '../b.dart';

// bad
import 'dart:a';
import 'package:a/a.dart';
import 'dart:b';
import '../b.dart';
import 'package:b/b.dart';
import 'a.dart';
```
</td>
</tr>

<tr id="2.4">
<td>

#### 2.4
</td>
<td>

Use curly braces for all flow control statements. <br>
**Exception**: When you have an if statement with no else clause and the whole if statement fits on one line, you can omit the braces.

</td>
<td>

```dart
// good
if (isTrue) {
  print('true');
}
if (arg == null) return true;

// bad
if (isTrue) 
  print('true');
```
</td>
</tr>

<tr id="2.5">
<td>

#### 2.5
</td>
<td>

**Keep functions short and focused.** <br>
Write functions that are focused on a single task and keep them short. This can help make your code more modular and easier to read. Avoid writing functions that are too long or that try to do too much. Instead, break complex tasks down into smaller functions that can be easily understood.

</td>
<td>

```dart
//for exam: We will download image from url, 
//then scale it and  generate thumbnail
cacheImage() async{
    await downloadImage();
    await createThumbnail();
}

createThumbnail() async{
    await resizeImage();
    await saveThumbnail();
}
```
</td>
</tr>
</table>

<p align="right">(<a href="#flutter-coding-rules">back to top</a>)</p>

## 3. Comment

Doc comments are especially handy because [dart doc](https://dart.dev/tools/dart-doc) parses them and generates beautiful doc pages from them. <br>
You don’t have to document every single library, top-level variable, type, and member, but you should document most of them.

<table>
<tr id="3.1">
<td width="5%">

#### 3.1
</td>
<td width="50%">Format comments like sentences and capitalize the first word.</td>
<td width="45%">

```dart
// In case no item in list, we do nothing
if (!hasItems) return false;
```
</td>
</tr>

<tr id="3.2">
<td>

#### 3.2
</td>
<td>

Use **///** to document members and types.</td>
<td>

```dart
/// The number of item in array.
int get length => ...
```
</td>
</tr>

<tr id="3.3">
<td>

#### 3.3
</td>
<td>You can use markdown on comments. Use it to give usage example of a funtion or class so others can learn quickly.</td>
<td>

```dart
/// This is a test class
///
///```dart
///final a = HelloWorld();
///```
class HelloWorld {}
```
It will generate the document like this

![Sample markdown comment](./images/flutter/markdown_comment.png)

</td>
</tr>

<tr id="3.4">

<td>

#### 3.4
</td>
<td>

**USE** english for comments.
</td>
<td>

```dart
//good
final List<Student> students = [];//list of students

//bad
final List<Student> students = [];//mảng chứa các sinh viên
```
</td>
</tr>
</table>

<p align="right">(<a href="#flutter-coding-rules">back to top</a>)</p>

## 4. Usage

<table>

<tr id="4.1">
<td width="5%">

#### 4.1
</td>
<td width="50%">

Avoid using **as** instead, use **is** operator
</td>
<td width="45%">

```dart
(item as Animal).name = 'Lion'; //bad

if (item is Animal)item.name = 'Lion';//good
```
</td>
</tr>

<tr id="4.2">
<td>

#### 4.2
</td>
<td>

If you choose to use **library** and **part of** to describe libraries with related files, with part files you should specify the library by path, not name.
</td>
<td>

```dart
library my_lib;
part './lib_a.dart';

// The part file should use the library file’s URI:
part of '../../my_library.dart';//good

// Not the library name:
part of my_library;//bad
```
</td>
</tr>

<tr id="4.3">
<td>

#### 4.3
</td>
<td>

**DON’T** import libraries that are inside the src directory of another package.
The src directory under lib is specified to contain libraries private to the package’s own implementation, they are free to make sweeping changes to code under src without it being a breaking change to the package.
</td>
<td>
For example, say your directory structure looks like this:

```dart
package_a
└─ lib
    └─src
        └─ private_lib.dart

// private_lib.dart:
library private_lib;

//we WON'T import private_lib into any our files
```
</td>
</tr>

<tr id="4.4">
<td>

#### 4.4
</td>
<td>

**DON’T** allow an import path to reach into or out of lib.
</td>
<td>
For example, say your directory structure looks like this:

```dart
my_package
└─ lib
   └─ api.dart
   test
   └─ api_test.dart

// And say api_test.dart imports api.dart in two ways:
import '../lib/api.dart'; //bad
import 'package:my_package/api.dart'; //good
```
</td>
</tr>

<tr id="4.5">
<td>

#### 4.5
</td>
<td>

When an import does not reach across lib, **PREFER** using relative imports. They’re shorter.
</td>
<td>
For example, say your directory structure looks like this:

```dart
my_package
└─ lib
   ├─ src
   │  └─ stuff.dart
   │  └─ utils.dart
   └─ api.dart
   test
   │─ api_test.dart
   └─ test_utils.dart

// lib/api.dart:
import 'src/stuff.dart';
import 'src/utils.dart';

// lib/src/utils.dart:
import '../api.dart';
import 'stuff.dart';

//test/api_test.dart:
import 'package:my_package/api.dart'; 
import 'test_utils.dart'; 
```
</td>
</tr>

<tr id="4.6">
<td>

#### 4.6
</td>
<td>

**AVOID** using late variables if you need to check whether they are initialized. Dart offers no way to tell if a late variable has been initialized or assigned to. If you access it, it either immediately runs the initializer (if it has one) or throws an exception.
</td>
<td>

```dart
class Student {
    late String name;

    Student(){
        name = "test";//Should init value at constructor
    }

    // Make sure name was initialized at somewhere behind,
    // or this code will throw an exception
    bool get isNameEmpty => return name.isEmpty; 
}
```
</td>
</tr>

<tr id="4.7">
<td>

#### 4.7
</td>
<td>

**DON'T** use the same name for properties and local variables, the compiler does not know exactly which variable should be used.
</td>
<td>

```dart
class Student {
    late String name;
    
    changeName({required String name}){        
        // Below 'name' is parameter or class property?
        if(name.isEmpty){}
    }
}
```
</td>
</tr>

<tr id="4.8">
<td>

#### 4.8
</td>
<td>

**PREFER** using interpolation to compose strings and values. 
</td>
<td>

```dart
'You are ${year - birth} years old.'; //good
'You are ' + (year - birth).toString() + ' y...'; //bad
```
</td>
</tr>

<tr id="4.9">
<td>

#### 4.9
</td>
<td>

**DO** use whereType() to filter a collection by type.
</td>
<td>

```dart
var objects = [1, 'a', 2, 'b', 3];
var ints = objects.whereType<int>();//good
var ints = objects.where((e) => e is int).cast<int>();//bad
```
</td>
</tr>

<tr id="4.10">
<td>

#### 4.10
</td>
<td>

**AVOID** using cast(). The cast() method returns a lazy collection that checks the element type on every operation. If you perform only a few operations on only a few elements, that laziness can be good. But in many cases, the overhead of lazy validation and of wrapping outweighs the benefits.
</td>
<td>

```dart
List<int> singletonList(int value) {
  var list = <int>[];//good
  var list = []; // bad List<dynamic>.
  list.add(value);
  return list;
}
```
</td>
</tr>

<tr id="4.11">
<td>

#### 4.11
</td>
<td>

Use **final** for local variables that are not reassigned (or read-only property) and **var** for those that are.</td>
<td>

```dart
class Student {
    final String name;//not reassigned
    var int? score; //reassigned
    Student({required this.name, this.score});
}
```

</td>
</tr>

<tr id="4.12">
<td>

#### 4.12
</td>
<td>

**CONSIDER** using **=>** for simple members.</td>
<td>

```dart
double get area => (right - left) * (bottom - top); //good
double get area {
    return (right - left) * (bottom - top); //bad
} 

```

</td>
</tr>

<tr id="4.13">
<td>

#### 4.13
</td>
<td>

**DON’T** use **new**. Dart 2 makes the new keyword optional. Even in Dart 1, its meaning was never clear because factory constructors mean a new invocation may still not actually return a new object.</td>
<td>

```dart
final student = Student();//good
final student = new Student();//bad
```
</td>
</tr>

<tr id="4.14">
<td>

#### 4.14
</td>
<td>

**Method cascades** are a better solution for chaining method calls.</td>
<td>

```dart
var buffer = StringBuffer()
  ..write('one')
  ..write('two')
  ..write('three');
```
</td>
</tr>

<tr id="4.15">
<td>

#### 4.15
</td>
<td>

**AVOID** writing incomplete generic types. The goal of writing a type annotation or type argument is to pin down a complete type, if you write the name of a generic type but omit its type arguments, you haven’t fully specified the type.
<td>

```dart
var completer = Completer<Map<String,int>>(); //good
var completer = Completer<Map>(); //bad

```
</td>
</tr>

<tr id="4.16">
<td>

#### 4.16
</td>
<td>

**AVOID** using **dynamic** unless you want to disable static checking. The type **dynamic** not only accepts all objects, but it also permits **all operations**. Any member access on a value of type **dynamic** is allowed at compile time, but may fail and throw an exception at runtime.
</td>

<td>

```dart
calculateValue(dynamic value){
    if(value.isEmpty){
        // you expect value is string or array
        // but it can be an int, double or null, 
        // or even a function
        // this is so risky.
    }
}
```
</td>
</tr>

<tr id="4.17">
<td>

#### 4.17
</td>
<td>

**DO** override **hashCode** if you override **==**.
</td>
<td>

```dart
class Student {
  final int studentId;
  Student({required this.studentId});
  @override
  int get hashCode => studentId;

  @override
  bool operator ==(Object other) =>
      other is Student && hashCode == other.hashCode;
}
```
</td>
</tr>

<tr id="4.18">
<td>

#### 4.18
</td>
<td>Use class to define your constants. Define multiple classes to break constants into logical sections.</td>
<td>

```dart
class Fonts {
    static const String spaceGrotesk = 'SpaceGrotesk';
}

class Urls {
    static const String apiUrl = 'http://localhost';
}

// You can also use constants 
// to define some repeating layouting numbers
class CommonWidgets {
    static const Widget Logo = Text('Hello');
}

```
</td>
</tr>

<tr id="4.19">
<td>

#### 4.19
</td>
<td>

**Avoid large functions and widgets.**
- Split large function into smaller functions.
- Split large widget into multiple (private or reusable) widgets.
- Use widget class rather than function returning widget.
</td>

<td>

```dart
//We split main screen to following structure
MainScreen
└─ MainView
   └─ Tabbar
   └─ TabContentView
   
class MainScreen extends StatefulWidget{
    final view = MainView();
}

class MainView extends StatefulWidget{
    final tabbar = Tabbar();
    final contentView = TabContentView();
}

class Tabbar extends StatefulWidget{}

class TabContentView extends StatefulWidget{}
```
</td>
</tr>

<tr id="4.20">
<td>

#### 4.20
</td>
<td>When you have to meet certain criteria to continue execution, try to exit early.</td>
<td>

```dart
//good
if (!isTrue) {
  return;
}

//bad
if (isTrue) {
  //...
}else {
    return;
}
```
</td>
</tr>

<tr id="4.21">
<td>

#### 4.21
</td>
<td>

Use  **??** and **?.** operators.
</td>
<td>

```dart
final flag = a == null ? b : a;//bad
final flag = a  ?? b;//good

final flag = a == null ? null : a.b;//bad
final flag = a?.b;
```
</td>
</tr>

<tr id="4.22">
<td>

#### 4.22
</td>
<td>Use spread collections.</td>
<td>

```dart
//bad
var y = [4,5,6];
var x = [1,2];
x.addAll(y);

//good
var y = [4,5,6];
var x = [1,2,...y];
```
</td>
</tr>

<tr id="4.23">
<td>

#### 4.23
</td>
<td>

Use **ListView.builder()** for a long list instead of **ListView()**.
</td>
<td>

```dart
final List<Widget> children = List.generate(
    1000, 
    (index) => Container()
);

//good
final listView = ListView.builder(
    itemBuilder: (_,index)=>children[index]
);

//bad
final listView = ListView(children: children);
```
</td>
</tr>

<tr id="4.24">
<td>

#### 4.24
</td>
<td>

Use **asserts** to detect contract violations and verify invariants.
</td>
<td>

```dart
validateValue(dynamic value){
    assert(value != null);
}
```
</td>
</tr>

<tr id="4.25">
<td>

#### 4.25
</td>
<td> 

If you want a empty widget when loading data for example, use **SizedBox**, it will create an empty widget with zero width and height. </td>
<td>

```dart

if(loading)SizedBox() : CustomWidget();
```
</td>
</tr>

<tr id="4.26">
<td>

#### 4.26
</td>
<td>

If you want to control events or do something with a widget, create a controller to do that instead of calling directly functions inside that widget. <br>
You can refer [this link](https://www.flutterclutter.dev/flutter/tutorials/create-a-controller-for-a-custom-widget/2021/2149/) about how to create a controller 

</td>
<td>

```dart
class MyWidgetController {
    reloadWidget(){
        //reload MyWidget
    }
}

class MyWidget{
    final MyWidgetController? controller;
    reload(){

    }
}

//good
final controller = MyWidgetController();
final widget = MyWidget(controller:controller);
controller.reloadWidget();

//bad
final widget = MyWidget();
widget.reload();

```
</td>
</tr>

<tr id="4.27">
<td>

#### 4.27
</td>
<td>

**Avoid interleaving multiple concepts together.**<br>
Each API should be self-contained and should not know about other features. Interleaving concepts leads to complexity.<br>
</td>
<td>

```dart
// LoginApi does not know about request(),
// it leave that for class RequestApi
// After success it parse response or show error message

abstract class RequestApi{
    final String url;
    final String method;

    request();
    onSuccessed();
    onFailed();
}

class LoginApi extends RequestApi{
    UserModel? user;
    LoginApi({this.url = "login",this.url = "method"});

    onSuccessed(){
        //user = parse responsee
    }

    onFailed(){
        //show message
    }
}

final loginApi = LoginApi();
await loginApi.request();

```
</td>
</tr>

<tr id="4.28">
<td>

#### 4.28
</td>
<td>

**Be explicit about dispose() and the object lifecycle.**<br>

If your class has a clear "end of life", for example, provide a dispose() method to clean up references such as listeners that would otherwise prevent some objects from getting garbage collected.
</td>
<td>

```dart
class CustomWidget {

    final textController = TextEditingController();

    dispose(){
        // dispose TextEditingController when widget disposed
        textController.dispose();
        super.dispose();
    }
}
```
</td>
</tr>
</table>

<p align="right">(<a href="#flutter-coding-rules">back to top</a>)</p>



## Refs
- https://github.com/flutter/flutter/wiki/Style-guide-for-Flutter-repo#write-test-find-bug
- https://dart.dev/guides/language/effective-dart/style
- https://www.flutterclutter.dev/flutter/tutorials/create-a-controller-for-a-custom-widget/2021/2149/
