<?php

declare(strict_types=1);

namespace Huttopia\ConsoleBundle\DependencyInjection;

use Symfony\Component\Config\{
    Definition\Builder\TreeBuilder,
    Definition\ConfigurationInterface
};

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('console');

        $rootNode
            ->children()
                ->arrayNode('excluded')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('databases')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('list')
                    ->children()
                        ->integerNode('symfonyVersionVerbosityLevel')->defaultValue(0)->end()
                        ->integerNode('usageVerbosityLevel')->defaultValue(0)->end()
                        ->integerNode('optionsVerbosityLevel')->defaultValue(0)->end()
                        ->integerNode('availableCommandsVerbosityLevel')->defaultValue(0)->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
