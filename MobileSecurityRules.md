# Mobile Security Rules


## Table of Contents

### 1. Ensure data storage is secured

Where your app’s data is stored and processed will factor into your app’s ability to comply with user data regulations and overall security. When possible, best practice dictates that confidential user data should never be stored on the user’s mobile device or your servers.

  - [Encryption in Flutter] (https://pub.dev/packages/encrypt)
  - [Encryption in iOS] (https://riptutorial.com/swift/example/23651/aes-encryption-in-cbc-mode-with-a-random-iv--swift-3-0-)
  - [Encryption in Android] (https://developer.android.com/guide/topics/security/cryptography?authuser=1)
  
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

- Store data in external storage based on use case
  - Use external storage for large, non-sensitive files that are specific to your app as well as files that your app shares with other apps. The specific APIs that you use depend on whether your app is designed to access app-specific files or access shared files.
  - If your app interacts with a removable external storage device, keep in mind that the user might remove the storage device while your app is trying to access it. Include logic to verify that the storage device is available.

- Keep updating SDK (Flutter), Editor (Xcode, Android studio)
  - You may miss out on important security updates if you are using non-updated versions. Such versions may not include security fixes usually found in the current versions.

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

### 8. Keep services and dependencies up to date 

Most apps use external libraries and device system information to complete specialized tasks. By keeping your app's dependencies up to date, you make these points of communication more secure.

You also need update SDK (Flutter), Editor (Xcode, Android studio) to make sure include security fixes usually found in the current versions.

### Refs

- https://www.appsealing.com/flutter-security/
- https://developer.android.com/topic/security/best-practices?authuser=1
- https://developer.apple.com/app-store/user-privacy-and-data-use/