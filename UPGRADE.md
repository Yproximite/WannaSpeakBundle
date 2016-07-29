# Upgrade

This document will tell you how to upgrade from one version to one other. 

# Upgrade from 1.0.x to 2.0

You need to install a HTTP client that provides the virtual package 
[php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation).
 Then you need to do one of the following things: 
 
 * Add the client's service name to `wanna_speak.http_client`. (Preferably with help from [HttplugBundle](https://github.com/php-http/HttplugBundle)
