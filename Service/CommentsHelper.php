<?php

namespace Youshido\CommentsBundle\Service;


use Youshido\CommentsBundle\Document\CommentableInterface;
use Youshido\CommentsBundle\Security\Voter\CommentVoter;
use Youshido\CommentsBundle\Document\Comment;
use Youshido\CommentsBundle\Document\CommentInterface;
use Youshido\GraphQLExtensionsBundle\Helper\BaseHelper;

class CommentsHelper extends BaseHelper
{
    /** @var  CommentsManager */
    private $commentsManager;

    private $modelClass;

    public function __construct(CommentsManager $commentsManager, $modelClass)
    {
        $this->commentsManager = $commentsManager;
        $this->modelClass      = $modelClass;
    }

    public function getComments($args)
    {
        $object = $this->getObject($this->modelClass, $args['modelId']);

        if (is_null($object)) {
            throw $this->createNotFoundException('Object not found');
        }

        return $this->commentsManager->getCursoredComments($args);
    }

    public function createComment($args)
    {
        /** @var CommentableInterface $object */
        $object = $this->getObject($this->modelClass, $args['modelId']);

        if (is_null($object)) {
            throw $this->createNotFoundException('Object not found');
        }

        if (!empty($args['parentId'])) {
            $parentComment = $this->getOm()->getRepository('CommentsBundle:Comment')->findOneBy([
                '_id'     => new \MongoId($args['parentId']),
                'modelId' => new \MongoId($args['modelId'])
            ]);

            if (is_null($parentComment)) {
                throw $this->createInvalidParamsException('This object doesn\'t have comment with id ' . $args['parentId']);
            }
        }

        return $this->commentsManager->createComment($object, $args['content'], ($args['parentId'] ?? null));
    }

    public function voteForComment($commentId, $value)
    {
        $comment = $this->getOm()->getRepository(Comment::class)->find($commentId);

        if (is_null($comment)) {
            throw $this->createNotFoundException('Comment not Found');
        }

        if ($value > 0) {
            $this->commentsManager->upvote($comment);
        } else {
            $this->commentsManager->downvote($comment);
        }

        return $comment;
    }

    public function editComment($commentId, $content)
    {
        /** @var CommentInterface $comment */
        $comment = $this->getObject(Comment::class, $commentId, CommentVoter::EDIT);

        $comment->setContent($content);

        $this->getOm()->flush();

        return $comment;
    }

    public function deleteComment($commentId)
    {
        /** @var CommentInterface $comment */
        $comment = $this->getObject(Comment::class, $commentId, CommentVoter::DELETE);
        $this->commentsManager->deleteComment($comment);

        return $comment->getSlug();
    }

}