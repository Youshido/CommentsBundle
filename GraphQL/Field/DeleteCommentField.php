<?php

namespace Youshido\CommentsBundle\GraphQL\Field;

use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * Class DeleteCommentField
 */
class DeleteCommentField extends AbstractField
{
    /**
     * @return StringType
     */
    public function getType()
    {
        return new StringType();
    }

    /**
     * @param FieldConfig $config
     */
    public function build(FieldConfig $config)
    {
        $config->addArguments([
            'commentId' => new NonNullType(new IdType()),
        ]);
    }

    /**
     * @param mixed       $value
     * @param array       $args
     * @param ResolveInfo $info
     *
     * @return mixed
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        return $info->getContainer()->get('comments_helper')->deleteComment($args['commentId']);
    }
}
