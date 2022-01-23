<?php

declare(strict_types=1);

namespace Williarin\WordpressInteropBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('williarin_wordpress_interop');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('default_entity_manager')->end()
                ->arrayNode('entity_managers')
                    ->arrayPrototype()
                        ->isRequired()
                        ->children()
                            ->scalarNode('connection')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('tables_prefix')->defaultValue('wp_')->end()
                        ->end()
                    ->end()
                    ->validate()
                        ->ifEmpty()
                        ->thenInvalid('You must define at least one entity manager.')
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
