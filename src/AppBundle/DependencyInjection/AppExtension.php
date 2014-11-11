<?php
namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class AppExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->applyConfig('master', $container, $config['master']);
        $this->applyConfig('slave', $container, $config['slave']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    protected function applyConfig($type, ContainerBuilder $container, $config)
    {
        // Set storage
        $storage = $config['storage'];
        $container->setAlias($type . '.storage', 'storage.' . $storage);

        // Set path
        $path = $config['path'];
        $container->setParameter($type . '.path', $path);

        // Set filters
        $filters = $config['filters'];
        $container->setParameter($type . '.filters', $filters);

        if ('slave' == $type) {
            $pathTpl = $config['path_tpl'];
            $container->setParameter($type . '.path_tpl', $pathTpl);
        }
    }
}