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

        # Testing mode
        test: false
```

