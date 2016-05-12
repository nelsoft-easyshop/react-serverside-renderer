<?php

namespace React\Bundle\ServerSideRendererBundle\DependencyInjection;

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
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('react_server_side_renderer');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                ->arrayNode('renderer')
                    ->children()
                        ->scalarNode('render_server')->end()
                        ->scalarNode('is_enabled')->end()
                    ->end()
                ->end()
                ->arrayNode('twig_extension')
                    ->children()
                        ->scalarNode('src_path')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
