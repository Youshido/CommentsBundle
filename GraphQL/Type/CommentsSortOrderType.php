<?php

namespace Youshido\CommentsBundle\GraphQL\Type;

use Youshido\GraphQL\Type\Enum\AbstractEnumType;

/**
 * Class CommentsSortOrderType
 */
class CommentsSortOrderType extends AbstractEnumType
{
    const DESC = 'DESC';
    const ASC  = 'ASC';

    /**
     * @return array
     */
    public function getValues()
    {
        return [
            [
                'name'  => 'ASC',
                'value' => self::ASC,
            ],
            [
                'name'  => 'DESC',
                'value' => self::DESC,
            ],
        ];
    }
}
