<?php

namespace Youshido\CommentsBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Youshido\CommentsBundle\DependencyInjection\CommentsExtension;
use Youshido\CommentsBundle\DependencyInjection\CompilerPass\CommentsCompilerPass;

class CommentsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CommentsCompilerPass());

    }

}