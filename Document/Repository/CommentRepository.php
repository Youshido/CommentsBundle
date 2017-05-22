<?php

namespace Youshido\CommentsBundle\Document\Repository;

use Youshido\CommentsBundle\GraphQL\Type\CommentSortModeEnumType;
use Youshido\GraphQLExtensionsBundle\Document\Repository\CursorAwareRepository;

/**
 * Class CommentRepository
 */
class CommentRepository extends CursorAwareRepository
{
    /**
     * @param array $args
     * @param array $filters
     *
     * @return array
     */
    public function getCursoredList($args, $filters = [])
    {
        $filters['modelId'] = $args['modelId'];
        if (!empty($args['sortMode'])) {
            switch ($args['sortMode']) {
                case CommentSortModeEnumType::COMMENT_SORT_TYPE_BEST:
                    $args['sort'] = [
                        'field' => 'upvotesCount',
                        'order' => 1,
                    ];
                    break;

                case CommentSortModeEnumType::COMMENT_SORT_TYPE_NEWEST:
                    $args['sort'] = [
                        'field' => 'slug',
                        'order' => 1,
                    ];
                    break;
            }
        }

        return parent::getCursoredList($args, $filters);
    }

    /**
     * @param array $filters
     *
     * @return \Doctrine\ODM\MongoDB\Query\Builder
     */
    public function createQueryForFilters($filters)
    {
        $qb = $this->createQueryBuilder();

        if (!empty($filters['modelId'])) {
            $qb->addAnd(['modelId' => new \MongoId($filters['modelId'])]);
        }

        $qb->sort('slug', 'ASC');

        return $qb;
    }
}
