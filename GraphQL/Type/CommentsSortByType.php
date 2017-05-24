<?php

namespace Youshido\CommentsBundle\GraphQL\Type;

use Youshido\GraphQL\Type\Enum\AbstractEnumType;

/**
 * Class CommentsSortByType
 */
class CommentsSortByType extends AbstractEnumType
{
    const POPULARITY = 'POPULARITY';
    const DATE       = 'DATE';
    const SLUG       = 'SLUG';

    /**
     * @return array
     */
    public function getValues()
    {
        return [
            [
                'name'  => 'POPULARITY',
                'value' => self::POPULARITY,
            ],
            [
                'name'  => 'DATE',
                'value' => self::DATE,
            ],
            [
                'name'  => 'SLUG',
                'value' => self::SLUG,
            ],
        ];
    }
}
