<?php

namespace Youshido\CommentsBundle\GraphQL\Type;

use Youshido\GraphQL\Type\Enum\AbstractEnumType;

/**
 * Class CommentSortModeEnumType
 */
class CommentSortModeEnumType extends AbstractEnumType
{
    const COMMENT_SORT_TYPE_BEST   = 'BEST';
    const COMMENT_SORT_TYPE_NEWEST = 'NEWEST';

    /**
     * @return array
     */
    public function getValues()
    {
        return [
            [
                'name'  => self::COMMENT_SORT_TYPE_BEST,
                'value' => self::COMMENT_SORT_TYPE_BEST,
            ],
            [
                'name'  => self::COMMENT_SORT_TYPE_NEWEST,
                'value' => self::COMMENT_SORT_TYPE_NEWEST,
            ],
        ];
    }
}
