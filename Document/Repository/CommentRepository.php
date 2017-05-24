<?php

namespace Youshido\CommentsBundle\Document\Repository;

use Youshido\CommentsBundle\GraphQL\Type\CommentsSortByType;
use Youshido\CommentsBundle\GraphQL\Type\CommentsSortOrderType;
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
        $filters['modelId']  = $args['modelId'];
        $filters['parentId'] = $args['parentId'] ?? null;

        if (!empty($args['sortBy'])) {
            $order = $args['sortOrder'] === CommentsSortOrderType::DESC ? -1 : 1;

            switch ($args['sortBy']) {
                case CommentsSortByType::POPULARITY:
                    $field = 'popularRating';

                    break;

                case CommentsSortByType::SLUG:
                    $field = 'slug';

                    break;

                case CommentsSortByType::DATE:
                    $field = 'createdAt';

                    break;

                case CommentsSortByType::VOTES:
                    $field = 'upvotesCount';

                    break;

                case CommentsSortByType::REPLIES:
                    $field = 'repliesCount';

                    break;

                default:
                    throw new \InvalidArgumentException(sprintf('Not supported sortOrder "%s"', $args['sortOrder']));
            }

            $args['sort'] = ['field' => $field, 'order' => $order];
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

        if (!empty($filters['parentId'])) {
            $qb->addAnd(['parentId' => new \MongoId($filters['parentId'])]);
        }

        $qb->sort('slug', 'ASC');

        return $qb;
    }
}
