<?php
namespace Kna\BEMBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('kna_bem');

        $root
            ->children()
                ->scalarNode('block_function_name')
                    ->defaultValue('b')
                ->end()
            ->end()
        ;
        return $treeBuilder;
    }
}