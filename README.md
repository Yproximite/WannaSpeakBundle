# WannaSpeakBundle

Symfony bundle for the Wannaspeak API (http://fr.wannaspeak.com/)

![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/yproximite/wanna-speak-bundle)
![](https://img.shields.io/badge/Symfony-%5E4.4%20%7C%7C%20%5E5.3-blue)
![CI](https://github.com/Yproximite/WannaSpeakBundle/workflows/CI/badge.svg)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Yproximite/WannaSpeakBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Yproximite/WannaSpeakBundle/?branch=master)

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

```yaml
# config/packages/wanna_speak.yml:
wanna_speak:
    api:
        base_uri: 'https://www-2.wannaspeak.com/api/api.php' # default
        credentials:
            account_id: '9999999999' # required
            secret_key: '0000000000' # required
```
