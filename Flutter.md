
# Flutter Coding Rules

## Refs
- https://github.com/flutter/flutter/wiki/Style-guide-for-Flutter-repo#write-test-find-bug
- https://dart.dev/guides/language/effective-dart/style
- https://www.flutterclutter.dev/flutter/tutorials/create-a-controller-for-a-custom-widget/2021/2149/
  
## 1. Naming 

<table>
<tr>
<td width="50%">

Name of files, packages, directories, import prefixes use **lowercase_with_underscores**(use only lowercase letters, even for acronyms, and separate words with _ ) format. </td>
<td width="50%">

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
<tr>
<td>

Name of constant, variables, enum variables use lowerCamelCase(capitalize the first letter of each word, except the first which is always lowercase, even if it’s an acronym) format.

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
<td>

Use prefix **_** for private identifiers (classes, variable,...). </td>
<td>

```dart
class _PrivateClass {...}

final _privateVariable = true;
```

</td>
<tr>
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
<tr>
<td>

**DON’T** use prefix letters.</td>
<td>

```dart
const defaultTimeout = 10; //good
const kDefaultTimeout = 10;  //bad
```

</td>
</tr>
</tr>
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
<tr>
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
<tr>
<td>

**PREFER** naming a method **as___()** if it returns a different representation backed by the original object.</td>
<td>

```dart
var map = table.asMap();
var list = bytes.asFloat32List();
```
</td>
</tr>

<tr>
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

## 2. Styling

<table>
<tr>
<td>

**DO** use *mixin* to define a mixin type.</td>
<td>

```dart
mixin ClickableMixin  {}
```
</td>
</tr>

<tr>
<td width="50%">

Import packages by following order and sort each section by alphabetically.

```dart
dart: 
package:
relative imports (../foo.dart)
```
</td>
<td width="50%">

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
<tr>
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

<tr>
<td>

**Keep functions short and focused** <br>
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

## 3. Comment

Doc comments are especially handy because [dart doc](https://dart.dev/tools/dart-doc) parses them and generates beautiful doc pages from them. <br>
You don’t have to document every single library, top-level variable, type, and member, but you should document most of them.

<table>
<tr>
<td width="50%">Format comments like sentences and capitalize the first word.</td>
<td width="50%">

```dart
// In case no item in list, we do nothing
if (!hasItems) return false;
```
</td>
</tr>
<tr>
<td>

Use **///** to document members and types.</td>
<td>

```dart
/// The number of item in array.
int get length => ...
```
</td>
</tr>
<tr>
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
</table>


## 4. Usage

<table>
<tr>
<td width="50%">

If you choose to use **library** and **part of** to describe a libraries with related files, with part files you should specify the library by path, not name.
</td>
<td width="50%">

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
<tr>
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
<tr>
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
<tr>
<td>

When an import does not reach across lib, **Prefer** using relative imports. They’re shorter.
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
<tr>
<td>

**AVOID** late variables if you need to check whether they are initialized. Dart offers no way to tell if a late variable has been initialized or assigned to. If you access it, it either immediately runs the initializer (if it has one) or throws an exception.
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

<tr>
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
<tr>
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
<tr>
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

<tr>
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

<tr>
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
<tr>
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
<tr>
<td>

**DON’T** use **new**. Dart 2 makes the new keyword optional. Even in Dart 1, its meaning was never clear because factory constructors mean a new invocation may still not actually return a new object.</td>
<td>

```dart
final student = Student();//good
final student = new Student();//bad
```
</td>

</tr>

<tr>
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

<tr>
<td>

**AVOID** writing incomplete generic types. The goal of writing a type annotation or type argument is to pin down a complete type, if you write the name of a generic type but omit its type arguments, you haven’t fully specified the type.
<td>

```dart
var completer = Completer<Map<String,int>>(); //good
var completer = Completer<Map>(); //bad

```
</td>
</tr>
<tr>
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

<tr>
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
<tr>
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

<tr>
<td>

**Avoid large functions and widgets**
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
<tr>
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

<tr>
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

<tr>
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

<tr>
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

<tr>
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

<tr>
<td> 

If you want a empty widget when loading data for example, use **SizedBox**, it will create an empty widget with zero width and height. </td>
<td>

```dart

if(loading)SizedBox() : CustomWidget();
```
</td>
</tr>

<tr>
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

<tr>
<td>

**Avoid interleaving multiple concepts together**<br>
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
    onSucceess();
    onFailed();
}

class LoginApi extends RequestApi{
    UserModel? user;
    LoginApi({this.url = "login",this.url = "method"});

    onSucceess(){
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

<tr>
<td>

**Be explicit about dispose() and the object lifecycle**<br>

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
