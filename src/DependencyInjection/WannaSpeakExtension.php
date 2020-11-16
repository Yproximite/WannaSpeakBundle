<?php

declare(strict_types=1);

/**
 * WannaSpeak API Bundle
 *
 * @author Jean-Baptiste Blanchon <jean-baptiste@yproximite.com>
 */

namespace Yproximite\WannaSpeakBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class WannaSpeakExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $container->setParameter('wanna_speak.api.account_id', $config['api']['credentials']['account_id']);
        $container->setParameter('wanna_speak.api.secret_key', $config['api']['credentials']['secret_key']);
        $container->setParameter('wanna_speak.api.base_url', $config['api']['base_url']);
        $container->setParameter('wanna_speak.api.test', $config['api']['test']);
    }
}
