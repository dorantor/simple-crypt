# simple-crypt
Very simple lib for symmetric en/decrypt messages so they could be sent through untrusted channels.

Usage:
```php
// 32 byte string as key
$key = md5('simple-crypt'); // 1b8ee2dc7723b2846792349dd4c74dc2
$crypt = new \Dorantor\SimpleCrypt($key);

$eMessage = $crypt->encrypt('Secret message that should be read only by trusted ones');

// ...

$oMessage = $crypt->decrypt($eMessage);
```