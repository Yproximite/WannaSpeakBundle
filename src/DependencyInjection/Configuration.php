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
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('wanna_speak');
        $rootNode    = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('api')
                    ->children()
                        ->arrayNode('credentials')
                            ->children()
                                ->scalarNode('account_id')
                                    ->info('Account ID given by WannaSpeak\'s customer service')
                                    ->isRequired()
                                ->end()
                                ->scalarNode('secret_key')
                                    ->info('Secret key given by WannaSpeak\'s customer service')
                                    ->isRequired()
                                ->end()
                            ->end()
                        ->end()
                        ->scalarNode('base_uri')
                            ->defaultValue('https://www-2.wannaspeak.com/api/api.php')
                        ->end()
                        ->scalarNode('test')
                            ->info('The testing mode prevent any requests to be made, it can be useful for local development.')
                            ->defaultValue(false)
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
