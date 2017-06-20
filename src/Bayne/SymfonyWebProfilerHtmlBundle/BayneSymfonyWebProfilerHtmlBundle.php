<?php

namespace Bayne\SymfonyWebProfilerHtmlBundle;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BayneSymfonyWebProfilerHtmlBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        // Load this bundle's YAML service definition
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/Resources/config')
        );
        $loader->load('services.yml');
    }

    public function getParent()
    {
        return 'WebProfilerBundle';
    }

}
