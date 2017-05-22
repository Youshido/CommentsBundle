<?php

namespace Youshido\CommentsBundle\GraphQL\Field;

use Youshido\CommentsBundle\GraphQL\Type\CommentType;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\IdType;

/**
 * Class UpvoteCommentField
 */
class UpvoteCommentField extends AbstractField
{
    /**
     * @return CommentType
     */
    public function getType()
    {
        return new CommentType();
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
     * @return \Youshido\CommentsBundle\Document\Comment|\Youshido\CommentsBundle\Document\CommentInterface
     */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        return $info->getContainer()->get('comments_helper')->voteForComment($args['commentId'], 1);
    }
}
