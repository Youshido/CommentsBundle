<?php

namespace Youshido\CommentsBundle\GraphQL\Field;

use Youshido\CommentsBundle\GraphQL\Type\CommentsSortByType;
use Youshido\CommentsBundle\GraphQL\Type\CommentsSortOrderType;
use Youshido\CommentsBundle\GraphQL\Type\CommentType;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Relay\Connection\Connection;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQLExtension\Type\CursorResultType;

/**
 * Class CommentsField
 */
class CommentsField extends AbstractField
{
    /**
     * @return CursorResultType
     */
    public function getType()
    {
        return new CursorResultType(new CommentType());
    }

    /**
     * @param FieldConfig $config
     */
    public function build(FieldConfig $config)
    {
        $config->addArguments(array_merge(Connection::connectionArgs(), [
            'modelId'   => new NonNullType(new IdType()),
            'parentId'  => new IdType(),
            'sortBy'    => [
                'type'    => new CommentsSortByType(),
                'defaultValue' => CommentsSortByType::DATE,
            ],
            'sortOrder' => [
                'type'    => new CommentsSortOrderType(),
                'defaultValue' => CommentsSortOrderType::ASC,
            ],
        ]));
    }

    /**
     * @param mixed       $value
     * @param array       $args
     * @param ResolveInfo $info
     *
     * @return \Youshido\CommentsBundle\Document\CommentInterface[]
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        return $info->getContainer()->get('comments_helper')->getComments($args);
    }
}
