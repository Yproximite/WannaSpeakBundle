# Upgrade

This document will tell you how to upgrade from one version to one other. 

# Upgrade from 1.0.x to 2.0

You need to install a HTTP client that provides the virtual package 
[php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation).
Then you need to add the client's service name to `wanna_speak.http_client`. (Preferably with help from [HttplugBundle](https://github.com/php-http/HttplugBundle)

# Upgrade from 3.x to 4.x

- Service id `wanna_speak.api.statistics` became `Yproximite\WannaSpeakBundle\Api\Statistics`
- Service id `wanna_speak.http_client` became `Yproximite\WannaSpeakBundle\Api\WannaSpeakHttpClient`

# Upgrade from 4.x to 5.x

The whole bundle has been rewritten for the better! 

- The support of Symfony 3 has been removed, only Symfony 4 and 5 are supported 
- PHP-HTTP has been removed in favor of the Symfony HTTP Client 
- `Statistics` class does not implement call-tracking/sounds API anymore, each API have their dedicated classes
