<?php

namespace Youshido\CommentsBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Youshido\CommentsBundle\DependencyInjection\CompilerPass\CommentsCompilerPass;

/**
 * Class CommentsBundle
 */
class CommentsBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CommentsCompilerPass());
    }
}
