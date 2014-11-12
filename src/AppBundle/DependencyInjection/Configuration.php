<?php
namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sync');

        $rootNode
            ->children()
                ->arrayNode('master')
                    ->children()
                        ->scalarNode('storage')->isRequired()->end()
                        ->scalarNode('path')->isRequired()->end()
                        ->variableNode('filters')->end()
                    ->end()
                ->end()
                ->arrayNode('slave')
                    ->children()
                        ->scalarNode('storage')->isRequired()->end()
                        ->scalarNode('path')->isRequired()->end()
                        ->scalarNode('path_tpl')->isRequired()->end()
                        ->variableNode('filters')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
