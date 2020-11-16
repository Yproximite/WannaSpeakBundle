# WannaSpeakBundle

Symfony bundle for the Wannaspeak API (http://fr.wannaspeak.com/)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bfac8ac4-0f50-408d-8652-4b36738f94ee/small.png)](https://insight.sensiolabs.com/projects/bfac8ac4-0f50-408d-8652-4b36738f94ee) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Yproximite/WannaSpeakBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Yproximite/WannaSpeakBundle/?branch=master)

## Installation

```bash
composer require yproximite/wanna-speak-bundle
```

Update your `config/bundles.php`

```php
return [
    // ...
    Yproximite\WannaSpeakBundle\WannaSpeakBundle::class => ['all' => true],
];
```

## Configuration

``` yaml
// config/packages/wanna_speak.yml:
wanna_speak:
    api:
        base_url: 'https://www-2.wannaspeak.com/api/api.php' # default
        credentials:
            account_id: '9999999999' # required
            secret_key: '0000000000' # required
```
