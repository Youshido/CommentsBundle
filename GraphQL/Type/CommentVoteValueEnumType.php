<?php

namespace Youshido\CommentsBundle\GraphQL\Type;

use Youshido\GraphQL\Type\Enum\AbstractEnumType;

/**
 * Class CommentVoteValueEnumType
 */
class CommentVoteValueEnumType extends AbstractEnumType
{
    const UPVOTE   = 1;
    const DOWNVOTE = -1;

    /**
     * @return array
     */
    public function getValues()
    {
        return [
            [
                'name'  => 'UPVOTE',
                'value' => self::UPVOTE,
            ],
            [
                'name'  => 'DOWNVOTE',
                'value' => self::DOWNVOTE,
            ],
        ];
    }
}
