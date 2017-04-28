<?php

namespace Youshido\CommentsBundle\GraphQL\Field;

use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\StringType;

class DeleteCommentField extends AbstractField
{

    public function getType()
    {
        return new StringType();
    }

    public function build(FieldConfig $config)
    {
        $config->addArguments([
            'commentId' => new NonNullType(new IdType())
        ]);
    }

    public function resolve($value, array $args, ResolveInfo $info)
    {
        return $info->getContainer()->get('comments_helper')->deleteComment($args['commentId']);
    }


}