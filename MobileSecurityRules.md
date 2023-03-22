# Mobile Security Rules

<table>
<tr id="5.1">
<td width="5%">

**5.1**
</td>
<td width="50%">

**Ensure data storage is secured.** <br>
Where your app’s data is stored and processed will factor into your app’s ability to comply with user data regulations and overall security. When possible, best practice dictates that confidential user data should never be stored on the user’s mobile device or your servers.

</td>
<td width="45%">

```dart
// In case you MUST save confidential information, 
// make sure encrypt them before saving.
// You can use AES-CBC method for encryption and decryption
import 'package:encrypt/encrypt.dart';
final key = "This is the key 32 char, 256 bits...";
final text = "confidential information";

//Encrypt
Encrypted encrypted = encryptWithAES(key, text);
String base64Encrypted = encrypted.base64;

//Decrypt
String decryptedText = decryptWithAES(key, encrypted);

///Accepts encrypted data and decrypt it. Returns plain text
String decryptWithAES(String key, Encrypted encryptedData) {
  final cipherKey = Key.fromUtf8(key);
  //Using AES CBC encryption
  final encryptService = Encrypter(AES(cipherKey, mode: AESMode.cbc)); 
  //Here the IV is generated from key. 
  // This is for example only. Use some other text or random data as IV for better security.
  final initVector = IV.fromUtf8(key.substring(0, 16)); 
 
  return encryptService.decrypt(encryptedData, iv: initVector);
}
 
///Encrypts the given plainText using the key. Returns encrypted data
Encrypted encryptWithAES(String key, String plainText) {
  final cipherKey = Key.fromUtf8(key);
  final encryptService = Encrypter(AES(cipherKey, mode: AESMode.cbc));
  //Here the IV is generated from key. This is for example only. 
  //Use some other text or random data as IV for better security.
  final initVector = IV.fromUtf8(key.substring(0, 16)); 
 
  Encrypted encryptedData = encryptService.encrypt(plainText, iv: initVector);
  return encryptedData;
}
```

</td>
</tr>

<tr id="5.2">
<td>

**5.2**
</td>
<td>

**Protect your mobile communication** <br>

Data transfers flowing from the user side to the application, especially sensitive data such as login credentials and payment information, need proper security measures. <br>
End-to-end encryption, VPN, HTTPS, SSL, and TLS provide a sufficient amount of encryption that secures the data in transit.

</td>
<td>

```dart
final apiUrl = "https://www.abc.com";//good
final apiUrl = "http://www.abc.com";//bad
```
</td>
</tr>

<tr id="5.3">
<td>

**5.3**
</td>
<td>

**Secure third-party dependencies** <br>

</td>
</tr>
</table>
