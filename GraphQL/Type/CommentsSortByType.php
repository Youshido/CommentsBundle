<?php

namespace Youshido\CommentsBundle\GraphQL\Type;

use Youshido\GraphQL\Type\Enum\AbstractEnumType;

/**
 * Class CommentsSortByType
 */
class CommentsSortByType extends AbstractEnumType
{
    const POPULARITY = 'POPULARITY';
    const REPLIES    = 'REPLIES';
    const VOTES      = 'VOTES';
    const DATE       = 'DATE';
    const SLUG       = 'SLUG';

    /**
     * @return array
     */
    public function getValues()
    {
        return [
            [
                'name'        => 'POPULARITY',
                'value'       => self::POPULARITY,
                'description' => 'By votes  plus replies',
            ],
            [
                'name'        => 'DATE',
                'value'       => self::DATE,
                'description' => 'By creation date',
            ],
            [
                'name'        => 'SLUG',
                'value'       => self::SLUG,
                'description' => 'By root parent creation date [a, a1, a2, b, b1, c, d]',
            ],
            [
                'name'        => 'VOTES',
                'value'       => self::VOTES,
                'description' => 'By number of votes',
            ],
            [
                'name'        => 'REPLIES',
                'value'       => self::REPLIES,
                'description' => 'By number of replies',
            ],
        ];
    }
}
