<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load('Yproximite\WannaSpeakBundle\\', __DIR__.'/../..')
        ->exclude([
            __DIR__.'/../../DependencyInjection',
            __DIR__.'/../../Exception',
            __DIR__.'/../../Resources',
            __DIR__.'/../../WannaSpeakBundle.php',
        ]);

    $services->get(\Yproximite\WannaSpeakBundle\HttpClient::class)
        ->args([
            '$accountId' => '%wanna_speak.api.account_id%',
            '$secretKey' => '%wanna_speak.api.secret_key%',
            '$baseUrl'   => '%wanna_speak.api.base_url%',
            '$test'      => '%wanna_speak.api.test%',
        ]);
};
