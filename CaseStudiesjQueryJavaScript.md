# Case Studies jQuery & JavaScript


## Table of Contents

[**1. The event does not load jQuery in HTML** ](#1)

[**2. Use CSS class define styles to handle events in javascript**](#2)

[**3. Error when use event document.ready() and window.onload()**](#3)

[**4. Use jQuery.fn.extend namespace**](#4)

<br>

## 1. The event does not load jQuery in HTML

<table>
<tr id="1">

<td width="5%">

**1.1**
</td>
<td width="50%">

Inline Javascript should not be used in HTML. </td>
<td width="45%">

```dart
// Bad
<img id="imgLogo" width="300px"
  src="imageUrl" onload="document.getElementById('#title').show()" >

<h1 id="title" style="display: none;">
  Show on the title image
</h1>

// Good 👍 
//file .html
<img id="imgLogo" src="imageUrl" width="300px">
<h1 id="title" style="display: none;">
  Show on the title image
</h1>

//file .js
$('#imgLogo').on('load', function() {
  document.getElementById('#title').show();
  or
  $('#title').show();
});
```

</td>
</tr>
<tr>
<td>

**1.2**
</td>
<td>

jQuery should not be used in HTML
</td>
<td>

```dart
// Bad
<img id="imgLogo" src="imageUrl" onload="$('#title').show()" width="300px">
<h1 id="title" style="display: none;">
  Show on the title image
</h1>

// Good 👍 
//file .html
<img id="imgLogo" src="imageUrl" width="300px">
<h1 id="title" style="display: none;">
  Show on the title image
</h1>

//file .js
$('#imgLogo').on('load', function() {
  $('#title').show();
});

or

$(document).ready(function() {
  $('#title').show();
});
```

</td>
</tr>
</tr>

</table>

<br>

## 2. Use CSS class define styles to handle events in javascript

<table>
<tr id="2">
<td width="5%" >

**2.1**
</td>
<td width="50%">

Class names should be given meaning and clarity when used </td>
<td width="45%">

```dart
// Bad
<img class="abc" src="imageUrl">

// Good 👍 
<img class="img-logo" src="imageUrl">
```

</td>
</tr>
<tr>
<td>

**2.2**
</td>
<td>

When using an existing class, test it again
</td>
<td>

```dart
// file .html
<img class="pointer-item" src="image" width="300px">
<h1 class="pointer-item">pointer item test</h1>

// file .js
$('.pointer-item').click(function() {
  window.open('https://www.google.com/', '_blank');
});

// Bad
Use and do not check again

// Good 👍 
Check if the class you are using is affected by any events
```

</td>
</tr>
</tr>

</table>

<br>

## 3. Error when use event document.ready() and window.onload()

<table>
<tr id="3">
<td width="5%" >

**3.1**
</td>
<td width="50%">

Only one event document.ready() or window.onload() should be used </td>
<td width="45%">

```dart
// Bad
$(document).ready(function() {
  $.fn.common.defaultLeasePublishRequestFlg = $('input[name=defaultLeasePublishRequestFlg]').val();
});

$(window).on('load', function(){
  $('input[name=leasePublishRequestFlg]').val($.fn.common.defaultLeasePublishRequestFlg);
});

// Good 👍 
$(document).ready(function() {
   $('input[name=leasePublishRequestFlg]').val($('input[name=defaultLeasePublishRequestFlg]').val());
});

// Good 👍
$(window).on('load', function(){
  $('input[name=leasePublishRequestFlg]').val($('input[name=defaultLeasePublishRequestFlg]').val());
});
```

</td>
</tr>
<tr>
<td>

</tr>
</tr>

</table>

<br>

## 4. Use jQuery.fn.extend namespace

<table>
<tr id="4">
<td width="5%" >

**4.1**
</td>
<td width="50%">

Should use jQuery.fn.extend namespace to avoid calling functions with the same name in different javascript file </td>
<td width="45%">

```dart
// Bad
// file test1.js
function test() {
  return 'test1';
}

// file test2.js
function test() {
  return 'test2';
}

// file test3.js
$(document).ready(function() {
   test(); // 'test2'
});

// Good 👍 
// file test1.js
jQuery.fn.extend({
  test1: {
    test: function () {
      return 'test1';
    }
  }
});

// file test2.js
jQuery.fn.extend({
  test2: {
    test: function () {
      return 'test2';
    }
  }
});

// file test3.js
$(document).ready(function() {
  $.fn.test1.test(); // 'test1'
  $.fn.test2.test(); // 'test2'
});
```

</td>
</tr>
<tr>
<td>

</tr>
</tr>

</table>
