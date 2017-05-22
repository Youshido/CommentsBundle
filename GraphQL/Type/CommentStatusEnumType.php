<?php

namespace Youshido\CommentsBundle\GraphQL\Type;

use Youshido\CommentsBundle\Document\CommentStatus;
use Youshido\GraphQL\Type\Enum\AbstractEnumType;

/**
 * Class CommentStatusEnumType
 */
class CommentStatusEnumType extends AbstractEnumType
{
    /**
     * @return array
     */
    public function getValues()
    {
        return [
            [
                'name'  => 'ACTIVE',
                'value' => CommentStatus::ACTIVE,
            ],
            [
                'name'  => 'BLOCKED',
                'value' => CommentStatus::BLOCKED,
            ],
            [
                'name'  => 'DELETED',
                'value' => CommentStatus::DELETED,
            ],
            [
                'name'  => 'PENDING',
                'value' => CommentStatus::PENDING,
            ],
            [
                'name'  => 'SPAM',
                'value' => CommentStatus::SPAM,
            ],
        ];
    }
}
