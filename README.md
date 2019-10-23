# WannaSpeakBundle
Communicate with Wannaspeak API (http://fr.wannaspeak.com/)

[![Build Status](https://travis-ci.com/Yproximite/YproxMessagesBundle.svg?token=pNBs2oaRpfxdyhqWf28h&branch=master)](https://travis-ci.com/Yproximite/YproxMessagesBundle)
![](https://img.shields.io/badge/php->%207.3-blue)
![](https://img.shields.io/badge/Symfony-%5E4.3-blue)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bfac8ac4-0f50-408d-8652-4b36738f94ee/small.png)](https://insight.sensiolabs.com/projects/bfac8ac4-0f50-408d-8652-4b36738f94ee) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Yproximite/WannaSpeakBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Yproximite/WannaSpeakBundle/?branch=master)

## Installation

```bash
composer require yproximite/wanna-speak-bundle
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
        test: false

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
        test: false
    http_client: 'httplug.client'
```
