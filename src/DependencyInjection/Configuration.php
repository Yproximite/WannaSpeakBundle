<?php

declare(strict_types=1);

namespace Yproximite\WannaSpeakBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('wanna_speak');
        $rootNode    = $treeBuilder->getRootNode();

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
