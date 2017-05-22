<?php

namespace Youshido\CommentsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('graphql_extensions');

        $rootNode
            ->children()
            ->enumNode('storage')
            ->values(['s3', 'filesystem'])
            ->cannotBeEmpty()
            ->defaultValue('filesystem')
            ->end()
            ->enumNode('platform')
            ->values(['odm', 'orm'])
            ->cannotBeEmpty()
            ->defaultValue('orm')
            ->end()
            ->scalarNode('model')
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('max_depth')
            ->end()
            ->booleanNode('allow_anonymous')
            ->defaultValue(false)
            ->end()
            ->end();

        return $treeBuilder;
    }
}
