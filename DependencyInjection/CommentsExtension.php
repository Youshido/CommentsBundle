<?php

namespace Youshido\CommentsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class CommentsExtension
 *
 * @package Youshido\CommentsBundle\DependencyInjection
 */
class CommentsExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $this->setContainerParam($container, 'platform', $config['platform']);
        $this->setContainerParam($container, 'host', null);
        $this->setContainerParam($container, 'scheme', null);
        $this->setContainerParam($container, 'model', $config['model']);
        $this->setContainerParam($container, 'allow_anonymous', $config['allow_anonymous']);
        $this->setContainerParam($container, 'max_depth', empty($config['max_depth']) ? null : $config['max_depth']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    private function setContainerParam(ContainerBuilder $container, $parameter, $value)
    {
        $container->setParameter(sprintf('comments.config.%s', $parameter), $value);
    }
}
