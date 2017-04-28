<?php

namespace Youshido\CommentsBundle\GraphQL\Field;

use Youshido\CommentsBundle\GraphQL\Type\CommentSortModeEnumType;
use Youshido\CommentsBundle\GraphQL\Type\CommentType;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Relay\Connection\Connection;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQLExtension\Type\CursorResultType;

class CommentsField extends AbstractField
{
    public function getType()
    {
        return new CursorResultType(new CommentType());
    }

    public function build(FieldConfig $config)
    {
        $config->addArguments(array_merge(Connection::connectionArgs(), [
            'modelId'  => new NonNullType(new IdType()),
            'sortMode' => [
                'type'         => new CommentSortModeEnumType(),
                'defaultValue' => CommentSortModeEnumType::COMMENT_SORT_TYPE_NEWEST
            ],
        ]));
    }

    public function resolve($value, array $args, ResolveInfo $info)
    {
        return $info->getContainer()->get('comments_helper')->getComments($args);
    }
}