# Mobile Security Rules


## Table of Contents

[**1. Ensure data storage is secured**](#1-ensure-data-storage-is-secured)

[**2. Use only necessary permissions**](#2-use-only-necessary-permissions)

[**3. Secure network communication**](#3-secure-network-communication)

[**4. User Privacy and Data Use**](#4-user-privacy-and-data-use)

[**5. Secure your source code**](#5-secure-your-source-code)

[**6. Secure API calling**](#6-secure-api-calling)

[**7. Secure API keys**](#7-secure-api-keys)

[**8. Data storage location**](#8-data-storage-location)

[**9. Jailbreak detection**](#9-jailbreak-detection)

[**10. Keep services and dependencies up to date**](#10-keep-services-and-dependencies-up-to-date)

[**Refs**](#refs)

### 1. Ensure data storage is secured

Where your app’s data is stored and processed will factor into your app’s ability to comply with user data regulations and overall security. When possible, best practice dictates that confidential user data should never be stored on the user’s mobile device or your servers.

  - [Encryption in Flutter](https://pub.dev/packages/encrypt)
  - [Encryption in iOS](https://riptutorial.com/swift/example/23651/aes-encryption-in-cbc-mode-with-a-random-iv--swift-3-0-)
  - [Encryption in Android](https://developer.android.com/guide/topics/security/cryptography?authuser=1)
  
### 2. Use only necessary permissions

Your app should handle user data and personal information safely, with the appropriate level of permission.

Request only the minimum number of permissions necessary for your app to function properly. When possible, relinquish permissions when your app no longer needs them. 

When using a plugin, it must match the permissions with itself proper functions, for example, a camera plug in should require camera permission, or sound permission to record video, instead of location permission.

When you intend to use a permission, you shoule have a explaination popup about it before requesting permission. Specially, with **Push Notifcation** in iOS, this approach is [required](https://developer.apple.com/app-store/review/guidelines/#5.1.1).

Instead of requesting all permissions at the beginning, this should just be executed at once you use it.

### 3. Secure network communication

Your app should just communicate with a web server that has a certificate issued by a well-known, trusted certificate authority(CA), use an HTTPS request is recommended.
  
In iOS you can use [App Transport Security](https://developer.apple.com/documentation/bundleresources/information_property_list/nsapptransportsecurity) to:
-  Allow insecure loads for web views.
-  Enable additional security features like Certificate Transparency.
-  Reduce or remove security requirements for communication with particular servers.

In Android you can configure [network network security](https://developer.android.com/training/articles/security-config?authuser=1) to
- Configure a custom CA
- Limit the set of trusted CAs
- Trust additional CAs 

### 4. User Privacy and Data Use

Mobile applications should respect user privacy and handle personal information with care. Developers should provide users with clear and concise privacy policies, obtain user consent before collecting data, and avoid collecting unnecessary data.

When using third-party libraries, they also use other privacies (e.g Google Admods use advertising ID), so you need to check them carefully.

Should use third-party libraries are high-rated or from big companies like Google, they may have clear documents about their SDK,( e.g [Google admob](https://developers.google.com/admob/ios/data-disclosure))


### 5. Secure your source code
It is possible to reverse-engineer the source code. So we should obfuscate the source code.
The **--split-debug-info** option specifies the directory where Flutter outputs debug files. In the case of obfuscation, it outputs a symbol map
```bash
flutter build apk --obfuscate --split-debug-info=/<project-name>/<directory>
```
Once you’ve obfuscated your binary, save the symbols file. You need this if you later want to de-obfuscate a stack trace.

### 6. Secure API calling

When you run a API, assume your secret keys, tokens,.. are leak in some way, someone will try to make unauthorized requests.

We suggest you secure api calling with a paramter's header **request_key** (or any name you want), this parameter will take value from current GMT time encrypted with some secret encryption method defined in app (you can use **AES256**), but IMPORTANTLY, it must be **decrypt-able**.
Your api will decrypt that value and check the request time, as if it's in a acceptable time range(1 minutes for exam), api will execute normally.

That approach will make sure although your api keys are leak, they are expired at once others try using.

### 7. Secure API keys
Shouldn’t store API keys (Google map api key for example) in applications, instead we recommend fetching them dynamically from your application’s back end and make sure they are encrypted also.

### 8. Data storage location
- If the data is structured (e.g. data records), you may want to consider using a relational database such as Sqlite or NoSQL.
- When choosing a folder to save user files, consider the privacy and security implications of each option. If you are saving sensitive data, the Documents directory (iOS) or internal Storage (Android)  may be the best choice. 
- In iOS, if you need to save files that should be accessible across multiple devices, iCloud may be the best choice.
- In Android, if you need to save files that should be accessible to other apps or users, shared storage may be the best choice.
  - If your app interacts with a removable external storage device, keep in mind that the user might remove the storage device while your app is trying to access it. Include logic to verify that the storage device is available.

### 9. Jailbreak detection

If you app handle with high-security data (e.g credit card, banking,...), you need to check check installed device is jailbreaked to enable those functions or give a warning to user.
- [Flutter](https://pub.dev/packages/flutter_jailbreak_detection)
- [iOS](https://cocoapods.org/pods/IOSSecuritySuite)
- [Android](https://github.com/scottyab/rootbeer)

### 10. Keep services and dependencies up to date 

Most apps use external libraries and device system information to complete specialized tasks. By keeping your app's dependencies up to date, you make these points of communication more secure.

You also need update SDK (Flutter), Editor (Xcode, Android studio) to make sure include security fixes usually found in the current versions.

### Refs

- https://www.appsealing.com/flutter-security/
- https://developer.android.com/topic/security/best-practices?authuser=1
- https://developer.apple.com/app-store/user-privacy-and-data-use/