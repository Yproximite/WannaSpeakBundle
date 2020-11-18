# WannaSpeakBundle

Symfony bundle for the Wannaspeak API (http://fr.wannaspeak.com/)

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
