<?php

namespace Youshido\CommentsBundle\Document\Repository;

use AppBundle\GraphQL\Type\Comment\CommentSortModeEnumType;
use Youshido\GraphQLExtensionsBundle\Document\Repository\CursorAwareRepository;

class CommentRepository extends CursorAwareRepository
{
    public function getCursoredList($args, $filters = [])
    {
        $filters['showdownId'] = $args['showdownId'];
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


    public function createQueryForFilters($filters)
    {
        $qb = $this->createQueryBuilder();

        if (!empty($filters['showdownId'])) {
            $qb->addAnd(['modelId' => new \MongoId($filters['showdownId'])]);
        }

        $qb->sort('slug', 'ASC');

        return $qb;
    }
}