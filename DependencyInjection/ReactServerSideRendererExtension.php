<?php

namespace React\Bundle\ServerSideRendererBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ReactServerSideRendererExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $processedConfig = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // Once the services definition are read, get your service and add a method call to setConfig()
        
        $reactServerSideRendererDefinition = $container->getDefinition('react_server_side_renderer.renderer');
        $reactServerSideRendererDefinition->addMethodCall('setConfig', [
            $processedConfig['renderer']['render_server'],
            $processedConfig['renderer']['is_enabled']
        ]);
        
        $reactServerSideRendererDefinition = $container->getDefinition('react_server_side_renderer.renderer');
        $reactServerSideRendererDefinition->addMethodCall('setConfig', [$processedConfig['renderer']['render_server']]);

        $reactCustomTwigTagsDefinition = $container->getDefinition('react_server_side_renderer.twig_extension');
        $reactCustomTwigTagsDefinition->addMethodCall('setSourcePath', [$processedConfig['twig_extension']['src_path']]);
    }
}
