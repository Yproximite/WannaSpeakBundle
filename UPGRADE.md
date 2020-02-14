# Upgrade

This document will tell you how to upgrade from one version to one other. 

# Upgrade from 1.0.x to 2.0

You need to install a HTTP client that provides the virtual package 
[php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation).
Then you need to add the client's service name to `wanna_speak.http_client`. (Preferably with help from [HttplugBundle](https://github.com/php-http/HttplugBundle)

# Upgrade from 3.x to 4.x

## Breaking changes

- Service alias `wanna_speak.api.statistics` has been removed for `Yproximite\WannaSpeakBundle\Api\WannaSpeakApi`
- Service `Yproximite\WannaSpeakBundle\Api\Statistics` has been renamed to `Yproximite\WannaSpeakBundle\Api\WannaSpeakApi`
- Service alias `wanna_speak.http_client` has been removed for `Yproximite\WannaSpeakBundle\Api\WannaSpeakHttpClient`
- Method `Yproximite\WannaSpeakBundle\Api\Statistics#processResponse()` is now protected.
- Interface `Yproximite\WannaSpeakBundle\Api\StatisticsInterface` has been renamed to `Yproximite\WannaSpeakBundle\Api\WannaSpeakApiInterface`
- Signature of `Yproximite\WannaSpeakBundle\Api\StatisticsInterface#callTracking` has been modified.

## Features

- Exception `Yproximite\WannaSpeakBundle\Exception\WannaSpeakException` has been added.

## Misc

- `phoneDid` has been renamed to `trackingPhone`
- `phoneDest` has been renamed to `trackedPhone`

## Migration

### `Yproximite\WannaSpeakBundle\Api\StatisticsInterface#callTracking()`

This method was too specific for our own usage at Yproximite, so the signature has been rewritten.

Before:
```
callTracking(
    /* string */ $method,
    /* string */ $name,
    /* string */ $trackedPhone,
    /* string */ $trackingPhone,
    /* int */ $platformId,
    /* int */ $siteId,
    /* bool */ $callerId = false,
    /* string|null */ $leg1 = null,
    /* string|null */ $leg2 = null,
    /* string|null */ $phoneMobileNumberForMissedCall = null,
    /* string|null */ $smsSenderName = null,
    /* string|null */ $smsCompanyName = null
)
```
```php
<?php
$wannaSpeakApi->callTracking('add', 'A name', '33122334455', '33566778899', 1100, 13245);

// with caller/callee
$wannaSpeakApi->callTracking('add', 'A name', '33122334455', '33566778899', 1100, 13245, true, 'caller_message', 'callee_message');

// with SMS
$wannaSpeakApi->callTracking('add', 'A name', '33122334455', '33566778899', 1100, 13245, false, null, null, '33611223344', 'Sender name', 'Company name');
```

Now:
```
callTracking(
    string $method,
    string $name,
    string $trackedPhone,
    string $trackingPhone,
    array $additionalArgs = []
): array
```
```php
$wannaSpeakApi->callTracking('add', 'A name', '33122334455', '33566778899', [
    'tag1' => 1100,
    'tag2' => 13245,
]);

// with caller/callee
$wannaSpeakApi->callTracking('add', 'A name', '33122334455', '33566778899', [
    'tag1' => 1100,
    'tag2' => 13245,
    // caller/callee
    'tag3' => 'callerid:33566778899',
    'leg1' => 'caller_message',
    'leg2' => 'callee_message',
]);

// with SMS support
$wannaSpeakApi->callTracking('add', 'A name', '33122334455', '33566778899', [
    'tag1' => 1100,
    'tag2' => 13245,
    // sms 
    'sms' => '33611223344', 
    'tag4' => 'Sender name',
    'tag5' => 'Company name',
]);
```
