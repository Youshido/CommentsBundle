<?php

namespace Youshido\CommentsBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class CommentsCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $platform     = $container->getParameter('comments.config.platform');
        switch ($platform) {
            case 'orm':
                $container->setAlias('comments.om', 'doctrine.orm.entity_manager');
                $models['file'] = 'Youshido\GraphQLExtensionsBundle\Entity\File';
                break;

            case 'odm':
                $container->setAlias('comments.om', 'doctrine_mongodb.odm.document_manager');
                $models['file'] = 'Youshido\GraphQLExtensionsBundle\Document\File';
                break;
        }
    }


}