<?php

/**
 * WannaSpeak API Bundle
 *
 * @author Jean-Baptiste Blanchon <jean-baptiste@yproximite.com>
 */

namespace Yproximite\WannaSpeakBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Yproximite\WannaSpeakBundle\Api\WannaSpeakHttpClient;

/**
 * Class WannaSpeakExtension
 *
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class WannaSpeakExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $container->setParameter('wanna_speak.api.account_id', $config['api']['credentials']['account_id']);
        $container->setParameter('wanna_speak.api.secret_key', $config['api']['credentials']['secret_key']);
        $container->setParameter('wanna_speak.api.test', $config['api']['test']);
        $container->setParameter('wanna_speak.api.base_url', $config['api']['base_url'] ?? null);

        if (!empty($config['http_client'])) {
            $container->getDefinition(WannaSpeakHttpClient::class)->replaceArgument('$httpClient', new Reference($config['http_client']));
        }
    }
}
