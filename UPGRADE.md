# Upgrade

This document will tell you how to upgrade from one version to one other.

# Upgrade from 1.0.x to 2.0

You need to install a HTTP client that provides the virtual package
[php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation). Then you need to add the client's service name to `wanna_speak.http_client`. (Preferably with help
from [HttplugBundle](https://github.com/php-http/HttplugBundle)

# Upgrade from 3.x to 4.x

- Service id `wanna_speak.api.statistics` became `Yproximite\WannaSpeakBundle\Api\Statistics`
- Service id `wanna_speak.http_client` became `Yproximite\WannaSpeakBundle\Api\WannaSpeakHttpClient`

# Upgrade from 4.x to 5.x

The whole bundle has been rewritten for the better!

- The support of Symfony 3 has been removed, only Symfony 4 and 5 are supported
- Config `api.base_url` has been renamed to `api.base_uri` and is now optional
- PHP-HTTP has been removed in favor of the Symfony HTTP Client
- `Statistics` class does not implement call-tracking/sounds API anymore, each API have their dedicated implementations:
    - `Yproximite\WannaSpeakBundle\Api\CallTrackingsInterface` for `ct` API
    - `Yproximite\WannaSpeakBundle\Api\SoundsInterface` for `sounds` API
    - `Yproximite\WannaSpeakBundle\Api\StatisticsInterface` for `stats` API
- All API methods are designed:
    - To use required parameters as WannaSpeak API's required arguments
    - To use a parameter `$additionalArguments` for WannaSpeak API's optional arguments.
- Your code can depends of class `WannaSpeak` if you prefer to use one class to rule them all (WannaSpeak APIs)
- WannaSpeak API error are nicely handled and a specific exception is thrown given the status code, see [Exceptions for the API](./src/Exception/Api)

## Configuration

### Before

```yaml
# config/packages/wanna_speak.yml:
wanna_speak:
    api:
        credentials:
            account_id: '9999999999'
            secret_key: '0000000000'
        base_url: 'https://www-2.wannaspeak.com/api/api.php' # renamed to `base_uri` and optional
        http_client: '...' # removed
        test: true
```

### After

```yaml
# config/packages/wanna_speak.yml:
wanna_speak:
    api:
        credentials:
            account_id: '9999999999'
            secret_key: '0000000000'
        test: true
```

## Code

### Before

```php
<?php

use Yproximite\WannaSpeakBundle\Api\StatisticsInterface;

class MyClass
{
    private $statistics;

    public function __construct(StatisticsInterface $statistics)
    {
        $this->statistics = $statistics;
    }

    public function myMethod()
    {
        $this->statistics->callTracking('add', 'name', 'phoneDest', 'phoneDid' /* , and 8 parameters ... */);
        $this->statistics->listSounds();
        $this->statistics->getAllStats(/* ... */); // this method wasn't even in the StatisticsInterface...
    }
}
```

### After

```php
<?php

use Yproximite\WannaSpeakBundle\Api\CallTrackingsInterface;
use Yproximite\WannaSpeakBundle\Api\SoundsInterface;
use Yproximite\WannaSpeakBundle\Api\StatisticsInterface;

class MyClass
{
    private $callTrackings;
    private $statistics;
    private $sounds;

    public function __construct(CallTrackingsInterface $callTrackings, StatisticsInterface $statistics, SoundsInterface $sounds)
    {
        $this->statistics    = $statistics;
        $this->callTrackings = $callTrackings;
        $this->sounds        = $sounds;
    }

    public function myMethod(): void
    {
        $this->callTrackings->add('phoneDid', 'phoneDest', 'name', [
            'tag1' => '...',
            'tag2' => '...',
            'sms'  => '...',
            'leg1' => '...',
            'leg2' => '...',
        ]);

        $this->sounds->list();

        $this->statistics->did(/* ... */); // TODO: this method will maybe be renamed
    }
}
```

And with the "mother" class `WannaSpeak`:

```php
<?php

use Yproximite\WannaSpeakBundle\WannaSpeak;

class MyClass
{
    private $wannaSpeak;

    public function __construct(WannaSpeak $wannaSpeak)
    {
        $this->wannaSpeak = $wannaSpeak;
    }

    public function myMethod(): void
    {
        $this->wannaSpeak->callTrackings()->add('phoneDid', 'phoneDest', 'name', [
            'tag1' => '...',
            'tag2' => '...',
            'sms'  => '...',
            'leg1' => '...',
            'leg2' => '...',
        ]);

        $this->wannaSpeak->sounds()->list();

        $this->wannaSpeak->statistics()->did(/* ... */); // TODO: this method will maybe be renamed
    }
}
```