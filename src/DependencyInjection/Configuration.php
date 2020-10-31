<?php

declare(strict_types=1);

/**
 * WannaSpeak API Bundle
 *
 * @author Jean-Baptiste  Blanchon <jean-baptiste@yproximite.com>
 */

namespace Yproximite\WannaSpeakBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('wanna_speak');

        // @phpstan-ignore-next-line
        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $rootNode = $treeBuilder->root('wanna_speak'); // @phpstan-ignore-line
        }

        $rootNode
            ->children()
                ->arrayNode('api')
                    ->children()
                        ->arrayNode('credentials')
                            ->children()
                                ->scalarNode('account_id')->info('Account ID given by WannaSpeak\'s customer service')->isRequired()->end()
                                ->scalarNode('secret_key')->info('Secret key given by WannaSpeak\'s customer service')->isRequired()->end()
                            ->end()
                        ->end()
                        ->scalarNode('base_url')->info('Url Api endpoint')->isRequired()->end()
                        ->scalarNode('test')->defaultValue(false)->end()
                    ->end()
                ->end()
                ->scalarNode('http_client')->defaultValue(null)
            ->end();

        return $treeBuilder;
    }
}
