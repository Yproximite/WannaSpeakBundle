# WannaSpeakBundle
Communicate with Wannaspeak API (http://fr.wannaspeak.com/)

[![Build Status](https://travis-ci.com/Yproximite/YproxMessagesBundle.svg?token=pNBs2oaRpfxdyhqWf28h&branch=master)](https://travis-ci.com/Yproximite/YproxMessagesBundle)
![](https://img.shields.io/badge/php->%207.3-blue)
![](https://img.shields.io/badge/Symfony-%5E4.3-blue)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bfac8ac4-0f50-408d-8652-4b36738f94ee/small.png)](https://insight.sensiolabs.com/projects/bfac8ac4-0f50-408d-8652-4b36738f94ee) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Yproximite/WannaSpeakBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Yproximite/WannaSpeakBundle/?branch=master)

## Installation

```bash
composer require yproximite/wanna-speak-bundle symfony/http-client
```

## Configuration

### Credentials and url

``` yaml
// config/packages/wanna_speak.yaml:
wanna_speak:
    api:
        credentials:
            account_id: '9999999999'
            secret_key: '0000000000'

        base_url: https://www-2.wannaspeak.com/api/api.php

        # Optional, will disable API calls if `true`
        test: false
```

### HttpClient

You can choose to use your own [Symfony HttpClient](https://symfony.com/doc/current/components/http_client.html) by using config key `http_client`.

First let's define your scoped new HttpClient: 

```yaml
# framework.yaml
framework:
    http_client:
        scoped_clients:
            wannaspeak_api.client:
                # since "base_uri" has to be configured here, you can remove "wanna_speak.api.base_url" config 
                base_uri: https://www-2.wannaspeak.com/api/api.php
               
                # configure options... see https://symfony.com/doc/current/reference/configuration/framework.html#http-client
                timeout: 5
```

and then reference it in your WannaSpeak API configuration with the config key `http_client`:

```yaml
# config/packages/wanna_speak.yaml:
wanna_speak:
    api:
        credentials:
            account_id: '9999999999'
            secret_key: '0000000000'

        # You can remove "base_url" option and use "http_client"
        http_client: '@wannaspeak_api.client'

        # Optional, will disable API calls if `true`
        test: false
```

