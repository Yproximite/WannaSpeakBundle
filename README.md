# WannaSpeakBundle
Communicate with Wannaspeak API (http://fr.wannaspeak.com/)

## Installation

```bash
composer require yproximite/wanna-speak-bundle
```

*Note: It's your responsibility to use one of [those adapters](https://packagist.org/providers/php-http/client-implementation)*
*For example:

```bash
composer require php-http/guzzle6-adapter
```

Add to your AppKernel:

```php
new Yproximite\WannaSpeakBundle\WannaSpeakBundle(),
```

## Configuration

### Credentials and url

``` yaml
// app/config/config.yml:
wanna_speak:
    api:
        credentials:
            account_id: '9999999999'
            secret_key: '0000000000'
        base_url: https://www-2.wannaspeak.com/api/api.php

```


### Choose HTTP client

WannaSpeakBundle 2.0 is no longer coupled to Guzzle3. Thanks to [Httplug](http://docs.php-http.org/en/latest/index.html) you can now use any
library to transport HTTP messages. You can rely on [discovery](http://docs.php-http.org/en/latest/discovery.html) to automatically
find an installed client or you can provide a client service name to the configuration (see [HttplugBundle](https://github.com/php-http/HttplugBundle)). 

``` yaml
// app/config/config.yml:
wanna_speak:
    http_client: 'httplug.client'

```

### Full references

``` yaml
// app/config/config.yml:
wanna_speak:
    api:
        credentials:
            account_id: '9999999999'
            secret_key: '0000000000'
        base_url: https://www-2.wannaspeak.com/api/api.php
    http_client: 'httplug.client'
```
