<?php

namespace Youshido\CommentsBundle\GraphQL\Type;

use Youshido\CommentsBundle\Document\Comment;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Type\Scalar\BooleanType;
use Youshido\GraphQL\Type\Scalar\DateTimeType;
use Youshido\GraphQL\Type\Scalar\IdType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQLExtensionsBundle\GraphQL\Field\ResizableImageField;

/**
 * Class CommentType
 */
class CommentType extends AbstractObjectType
{
    /**
     * @param \Youshido\GraphQL\Config\Object\ObjectTypeConfig $config
     */
    public function build($config)
    {
        $config->addFields([
            'id'             => new IdType(),
            'author'         => new ObjectType([
                'name'   => 'Author',
                'fields' => [
                    'userId' => new IdType(),
                    'name'   => new StringType(),
                    new ResizableImageField('avatar'),
                ],
            ]),
            'hasVoted'       => [
                'type'    => new BooleanType(),
                'resolve' => function ($source, $args, ResolveInfo $info) {
                    if ($source instanceof Comment) {
                        return $info->getContainer()->get('comments_manager')->userHasVoted($source);
                    }
                },
            ],
            'slug'           => new StringType(),
            'upvotesCount'   => new IntType(),
            'downvotesCount' => new IntType(),
            'votesCount'     => [
                'type'    => new IntType(),
                'resolve' => function (Comment $source) {
                    return $source->getUpvotesCount() - $source->getDownvotesCount();
                },
            ],
            'content'        => new StringType(),
            'createdAt'      => new DateTimeType('c'),
            'parentId'       => new IdType(),
            'status'         => new CommentStatusEnumType(),
            'level'          => new IntType(),
        ]);
    }
}
