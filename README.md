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
        # Required
        credentials:
            account_id: '9999999999'
            secret_key: '0000000000'

        # Required
        base_url: https://www-2.wannaspeak.com/api/api.php

        # Optional, if you have defined scoped Symfony HTTP Client, you can use it here.
        # Documentation: https://symfony.com/doc/current/components/http_client.html#configuration
        http_client: '@my_http_client_for_wanna_speak'

        # Optional, will disable API calls if `true`
        test: false
```

