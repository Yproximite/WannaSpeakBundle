# WannaspeakBundle
Communicate with Wannaspeak API (http://fr.wannaspeak.com/)

## Installation

```bash
composer require php-http/guzzle6-adapter yproximite/wanna-speak-bundle
```

*Note: You can use any of [these adapters](https://packagist.org/providers/php-http/client-implementation)*

Add to your AppKernel:

```php
new Yproximite\WannaSpeakBundle\WannaSpeakBundle(),
```

Configure your application with your credentials.

``` yaml
// app/config/config.yml:
wanna_speak:
    api:
        credentials:
            account_id: '9999999999'
            secret_key: '0000000000'
        base_url: https://www-2.wannaspeak.com/api/api.php

```

## Choose HTTP client

WannaSpeakBundle 2.0 is no longer coupled to Guzzle3. Thanks to [Httplug](http://docs.php-http.org/en/latest/index.html) you can now use any
library to transport HTTP messages. We use [discovery](http://docs.php-http.org/en/latest/discovery.html) to automatically
find an installed client.
