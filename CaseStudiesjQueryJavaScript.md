# Case Studies jQuery & JavaScript


## Table of Contents

[**1. The event does not load jQuery in HTML** ](#1-the-event-does-not-load-jquery-in-html)

[**2. Use CSS class define styles to handle events in javascript**](#2-use-css-class-define-styles-to-handle-events-in-javascript)

[**3. Error when use event document.ready() and window.onload()**](#3-error-when-use-event-documentready-and-windowonload)

[**4. Use jQuery.fn.extend namespace**](#4-use-jqueryfnextend-namespace)

[**5. Should avoid using duplicate class/ID html attribute**](#5-should-avoid-using-duplicate-classid-html-attribute)

<br>

## 1. The event does not load jQuery in HTML

<table>
<tr id="1">

<td width="5%">

**1.1**
</td>
<td width="50%">

Inline event should not be used in HTML </td>
<td width="45%">

```dart
// Bad
<img id="imgLogo" width="300px"
  src="imageUrl" onload="$('#title').show()" >

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
```

</td>
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

Can use some other selector to declare event if necessary, such as: data-* , class, id. However, if this class also has an event assignment in the javascript file then you must name such as: 'js-{{class}}' </td>
<td width="45%">

```dart
// file .html
<img class="js-pointer-item" src="image" width="300px">
<h1 class="pointer-item">pointer item test</h1>

// file .js
$('.js-pointer-item').click(function() {
  window.open('https://www.google.com/', '_blank');
});
```


</td>
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

Only one event type document.ready() or window.onload() can be used in a page. If the case loads all the content, use window.onload(). However, it is recommended to use a document.ready method because this method can be used in many js files embedded in a page. If you need to wait for an element to finish loading, you can use the following </td>
<td width="45%">

```dart
  $(document).ready(function() {
      $('img').on('load', function() {
        $('#title').show();
      })
  });
```

</td>
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

</table>
<br>

## 5. Should avoid using duplicate class/ID html attribute

<table>
<tr id="5.1">
<td width="5%" >

**5.1**

</td>
<td width="50%">
Use class names carefully to avoid conflicts with existing JavaScript events.
</td>

<td width="45%">

```dart
// File .html

// Bad
<!-- Existing button -->
<button class="btn-change-time" width="50px">
  Change delivery time
</button>

<!-- New button -->
<button class="btn-change-time" width="30px">
  Change order time
</button>

// Good 👍
<!-- Existing button -->
<button class="btn-change-time" width="50px">
  Change delivery time
</button>

<!-- New button -->
<button class="btn-change-order-time" width="30px">
  Change order time
</button>

// File .js

// Existing JS event
$('.btn-change-time').on('click', function() {
  openCalendarPopup();
})
```

</td>
</tr>
<tr id="5.2">
<td width="5%" >

**5.2**

</td>
<td width="50%">
Use unique IDs for event bindings to ensure distinct functionality and avoid conflicts.
</td>

<td width="45%">

```dart
// Example 1

// File .html

// Bad

<!-- Existing button -->
<button id="btn-submit-order" class="btn-submit">
  Submit order
</button>

<!-- New button -->
<button id="btn-cancel-order" class="btn-submit">
  Cancel order
</button>

<script>
// Existing JS event
$('.btn-submit').on('click', function() {
  formValidate();
})
</script>

// Good 👍
<!-- Existing button -->
<button id="btn-submit-order" class="btn-submit">
  Submit order
</button>

<!-- New button -->
<button id="btn-cancel-order" class="btn-submit">
  Cancel order
</button>

<script>
// Existing JS event
$('#btn-submit-order').on('click', function() {
  formValidate();
})
</script>

// Example 2

// Bad
<input type="text" id="customer-name" value="Jane"><br>
<input type="text" id="customer-name" value="David"><br>

<script>
  console.log($('#customer-name').val());
  // => Result: Jane
</script>
```

</td>
</tr>
</table>