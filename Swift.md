# Flutter Coding Rules

## Table of Contents


## 1. Naming

<table>
<tr id="1.1">

<td width="5%">

**1.1**
</td>
<td width="50%">

Name of files, classes, protocol, type aliases use **UpperCamelCase**(capitalize the first letter of each word, including the first) format.
</td>

<td>

```swift

HomeViewController.swift

//HomeViewController with extension functions
HomeViewController+Extensions.swift

class HomeViewController:UIViewController {}

protocol Streamable

typealias MoneyAmount = Double

```
</td>
</tr>

<tr id="1.2">

<td>

**1.2**
</td>
<td>

Name of variables, methods, enum variables use **lowerCamelCase**(capitalize the first letter of each word, except the first which is always lowercase, even if it’s an acronym) format.
</td>

<td>

```swift

let studentName = "Hello"
func buttonClicked(_ sender: UIButton?) {}

public enum HTTPStatus: Int {
  case ok = 200
  case badRequest = 400
}
```
</td>
</tr>

<tr id="1.3">
<td>

**1.3**
</td>
<td>

Name of constants **SHOULD** use **UPPER_CASE_UNDERSCORE**(upper case all letters and separate words with _) format.
</td>

<td>

```swift
let TIMEOUT_SECONDS = 2

```
</td>
</tr>

<tr id="1.4">
<td>

**1.4**
</td>
<td>

Generic types should start with letter **T**, then **U, V** and so on. You can use different names to make usage of generics more clear.

</td>

<td>

```swift


public struct GenericStructure<T> {}

public struct ExtendStructure<Int> {}

```
</td>
</tr>

<tr id="1.5">
<td>

**1.5**
</td>
<td>

Names should be short and meaningful, **DON'T** use the misunderstanding names.

</td>

<td>

```swift

let max = 3;//bad
let maximumItemsCount = 3;//good

let i = 0;//bad
let studentIndex = 0;//good
```
</td>
</tr>

<tr id="1.6">
<td>

**1.6**
</td>
<td>

**Protocols** <br>
When picking a protocol name, always consider what your protocol is about.
</td>
<td>

```swift
//If it declares that type can be something else, 
//use Convertible as suffix
protocol JSONConvertible {}
protocol APIFilterConvertible {}

//If your protocol defines an action or capability, 
//use -able or -ible as suffix
protocol Streamable {}
protocol Mappable {}
protocol MemberContentVisible {}

//If your protocol serves as a data source or a delegate, 
//use with DataSource or Delegate as suffix.
protocol TableDataSource{}
```
</td>
</tr>

<tr id="1.7">
<td>

**1.7**
</td>
<td>
Restricted access control (internal, fileprivate, or private) is preferred for the purposes of hiding information from clients, rather than naming conventions.
</td>
<td>

```swift

private let name:String //good
private let _name:String //bad

```
</td>
</tr>

<tr id="1.8">
<td>

**1.8**
</td>
<td>

When the operation is **naturally described by a verb**, use the verb’s imperative for the mutating method and apply the “ed” or “ing” suffix to name its nonmutating counterpart.
</td>
<td>

```swift
//Mutating
x.sort()
x.append(y)

//Nonmutating
z = x.sorted()
z = x.appending(y)
```
</td>
</tr>

<tr id="1.9">
<td>

**1.9**
</td>
<td>

When the operation is **naturally described by a noun**, use the noun for the nonmutating method and apply the “form” prefix to name its mutating counterpart.
</td>
<td>

```swift
//Nonmutating
x = y.union(z)	
j = c.successor(i)	

//Mutating
y.formUnion(z)
c.formSuccessor(&i)
```
</td>
</tr>
</table>

## 2. Styling

<table>
<tr id="2.1">

<td width="5%">

**2.1**
</td>
<td width="50%">
Do not use semicolons at end of the line.
</td>
<td>

```swift
let name = "hello"; //bad
let name = "hello" //good
```
</td>

</table>


## 3. Comment

<table>
<tr id="3.1">
<td width="5%">

**3.1**
</td>
<td width="50%">
Documentation comments are written using the format where each line is preceded by a triple slash (///). Javadoc-style block comments (/** ... */) are not permitted.
</td>
<td>

```swift
/// Return numeric value from a string
func numericValue(of string: String) -> Int {
  // ...
}
```
</td>
</tr>

<tr id="3.2">
<td>

**3.2**
</td>
<td width="50%">
Comments begin with a brief single-sentence summary that describes the declaration.<br>
(This sentence may span multiple lines, but if it spans too many lines, the author should consider whether the summary can be simplified and details moved to a new paragraph.)
</td>
<td>

```swift
//good
/// The background color of the view.
var backgroundColor: UIColor

//bad
/// This property is the background color of the view.
var backgroundColor: UIColor
```
</td>
</tr>

<tr id="3.3">
<td>

**3.3**
</td>
<td>

Use **// MARK:** is strongly advised to group your functions, properties,.. as a logical order, which will be helpful for readers and future writers (including yourself). <br>
These comments are also interpreted by Xcode and provide bookmarks in the source window’s navigation bar.
</td>
<td>

```swift
// MARK: - View controller lifecycle methods
override func viewDidLoad() {}

override func viewWillAppear(_ animated: Bool) {}

// MARK: - User actions
@objc private func shareButtonClicked(_ sender: UIButton?) {}

@objc private func saveButtonClicked(_ sender: UIButton?) {}
```
</td>
</tr>

</table>

## 4. Usage
<table>
<tr id="4.1">
<td width="5%">

**4.1**
</td>
<td width="50%">
If you don't need a variable, use nameless binding.
</td>
<td>

```swift
_ = methodThatReturnsUnusedVariable()
```
</td>
</tr>

<tr id="4.2">
<td>

**4.2**
</td>
<td>

If a function call has multiple closure arguments, all arguments **SHOULD** be labeled.
</td>
<td>

```swift
//good
UIView.animate(
  withDuration: 0.5,
  animations: {
    // ...
  },
  completion: { finished in
    // ...
  })

//bad
UIView.animate(
  withDuration: 0.5,
  animations: {
    // ...
  }) { finished in
    // ...
  }
```
</td>
</tr>

<tr id="4.3">
<td>

**4.3**
</td>
<td>

**Take advantage of defaulted parameters** when it simplifies common uses. Any parameter with a single commonly-used value is a candidate for a default.
</td>
<td>

```swift
//bad
let order = lastName.compare(
  royalFamilyName, options: [], range: nil, locale: nil)

//good
let order = lastName.compare(royalFamilyName)
```
</td>
</tr>
</table>


## Refs
- https://www.swift.org/documentation/api-design-guidelines/
- https://google.github.io/swift
- 