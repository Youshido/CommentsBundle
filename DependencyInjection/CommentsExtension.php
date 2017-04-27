<?php

namespace Youshido\CommentsBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class CommentsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

//        $container->setParameter('graphql_extensions.files', $config['files']);
        $this->setContainerParam($container, 'platform', $config['platform']);
        $this->setContainerParam($container, 'host', null);
        $this->setContainerParam($container, 'scheme', null);
        $this->setContainerParam($container, 'allow_anonymous', $config['allow_anonymous']);
        $this->setContainerParam($container, 'max_depth', $config['max_depth']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    private function setContainerParam(ContainerBuilder $container, $parameter, $value)
    {
        $container->setParameter(sprintf('comments.config.%s', $parameter), $value);
    }

}