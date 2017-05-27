<?php

namespace Youshido\CommentsBundle\Service;

use Youshido\CommentsBundle\Document\Comment;
use Youshido\CommentsBundle\Document\CommentableInterface;
use Youshido\CommentsBundle\Document\CommentInterface;
use Youshido\CommentsBundle\Security\Voter\CommentVoter;
use Youshido\GraphQLExtensionsBundle\Helper\BaseHelper;

/**
 * Class CommentsHelper
 *
 * @package Youshido\CommentsBundle\Service
 */
class CommentsHelper extends BaseHelper
{
    /** @var  CommentsManager */
    private $commentsManager;

    /** @var string string */
    private $modelClass;

    /**
     * CommentsHelper constructor.
     *
     * @param CommentsManager $commentsManager
     * @param string          $modelClass
     */
    public function __construct(CommentsManager $commentsManager, $modelClass)
    {
        $this->commentsManager = $commentsManager;
        $this->modelClass      = $modelClass;
    }

    /**
     * @param array $args
     *
     * @return CommentInterface[]
     * @throws \Exception
     */
    public function getComments($args)
    {
        $object = $this->getObject($this->modelClass, $args['modelId']);

        if (null === $object) {
            throw $this->createNotFoundException('Object not found');
        }

        return $this->commentsManager->getCursoredComments($args);
    }

    /**
     * @param array $args
     *
     * @return CommentInterface
     * @throws \Exception
     */
    public function createComment($args)
    {
        /** @var CommentableInterface $object */
        $object = $this->getObject($this->modelClass, $args['modelId']);

        if (null === $object) {
            throw $this->createNotFoundException('Object not found');
        }

        if (!empty($args['parentId'])) {
            $parentComment = $this->getOm()->getRepository('CommentsBundle:Comment')->findOneBy([
                '_id'     => new \MongoId($args['parentId']),
                'modelId' => new \MongoId($args['modelId']),
            ]);

            if (null === $parentComment) {
                throw $this->createInvalidParamsException('This object doesn\'t have comment with id ' . $args['parentId']);
            }
        }

        return $this->commentsManager->createComment($object, $args['content'], !empty($args['parentId']) ? $args['parentId'] : null);
    }

    /**
     * @param string $commentId
     * @param int    $value
     *
     * @return Comment|CommentInterface
     */
    public function voteForComment($commentId, $value)
    {
        /** @var CommentInterface $comment */
        $comment = $this->getObject(Comment::class, $commentId);

        if ($value > 0) {
            $this->commentsManager->upvote($comment);
        } else {
            $this->commentsManager->downvote($comment);
        }

        return $comment;
    }

    /**
     * @param string $commentId
     *
     * @return Comment|CommentInterface
     */
    public function removeVoteForComment($commentId)
    {
        /** @var CommentInterface $comment */
        $comment = $this->getObject(Comment::class, $commentId);
        $this->commentsManager->removeVote($comment);

        return $comment;
    }

    /**
     * @param string $commentId
     * @param string $content
     *
     * @return Comment|CommentInterface
     */
    public function editComment($commentId, $content)
    {
        /** @var CommentInterface $comment */
        $comment = $this->getObject(Comment::class, $commentId, CommentVoter::EDIT);

        $comment->setContent($content);

        $this->getOm()->flush();

        return $comment;
    }

    /**
     * @param string $commentId
     *
     * @return mixed
     */
    public function deleteComment($commentId)
    {
        /** @var CommentInterface $comment */
        $comment = $this->getObject(Comment::class, $commentId, CommentVoter::DELETE);
        $this->commentsManager->deleteComment($comment);

        return $comment->getSlug();
    }

    /**
     * @return string
     */
    public function getModelClass()
    {
        return $this->modelClass;
    }

    /**
     * @param string $modelClass
     *
     * @return CommentsHelper
     */
    public function setModelClass($modelClass)
    {
        $this->modelClass = $modelClass;

        return $this;
    }
}
