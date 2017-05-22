<?php

namespace Youshido\CommentsBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Youshido\CommentsBundle\Document\Comment;
use Youshido\CommentsBundle\Document\CommentableInterface;
use Youshido\CommentsBundle\Document\CommentInterface;
use Youshido\CommentsBundle\Document\CommentVote;
use Youshido\CommentsBundle\Document\UserReference;
use Youshido\CommentsBundle\Event\CreateCommentEvent;
use Youshido\CommentsBundle\Event\DeleteCommentEvent;

/**
 * Class CommentsManager
 */
class CommentsManager
{
    /** @var ObjectManager|DocumentManager */
    private $om;

    /** @var TokenStorage */
    private $tokenStorage;

    /** @var */
    private $currentUser;

    /** @var bool */
    private $allowAnonymous = false;

    /** @var int */
    private $maxDepth;

    /** @var  EventDispatcherInterface */
    private $eventDispatcher;

    /**
     * CommentsManager constructor.
     *
     * @param ObjectManager            $om
     * @param TokenStorage             $tokenStorage
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(ObjectManager $om, TokenStorage $tokenStorage, EventDispatcherInterface $eventDispatcher)
    {
        $this->om              = $om;
        $this->tokenStorage    = $tokenStorage;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param CommentableInterface $object
     * @param string               $content
     * @param string               $parentId
     *
     * @return CommentInterface
     */
    public function createComment($object, $content, $parentId = null)
    {
        $comment = new Comment($content);
        $comment->setModelId(new \MongoId($object->getId()));
        $parentSlug = '';

        if (null !== $parentId) {
            $parent = $this->om->getRepository(Comment::class)->find($parentId);

            if (null !== $this->maxDepth && $parent->getLevel() + 1 > $this->maxDepth) {
                $comment->setLevel($this->maxDepth);
                if ($parent->getParentId()) {
                    $parent = $this->om->getRepository(Comment::class)->find($parent->getParentId());
                }
            } else {
                $comment->setLevel($parent->getLevel() + 1);
            }

            if ($parent) {
                $comment->setParentId($parentId);
                $parentSlug = $parent->getSlug() . '-';
            }
        }

        $comment->setCreatedAt(new \DateTime());
        $comment->setSlug($parentSlug . (new \DateTime())->format('Y.m.d.H.i.s-') . uniqid('', false));

        $this->processAuth($comment);

        $this->om->persist($comment);
        $this->om->flush($comment);

        $this->eventDispatcher->dispatch('comments_bundle.comment.create', new CreateCommentEvent($comment));

        return $comment;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getCurrentUser()
    {
        if (!$this->allowAnonymous && !$this->currentUser) {
            throw new \LogicException('Anonymous comments are not allowed');
        }

        return $this->currentUser;
    }

    /**
     * @param mixed $currentUser
     */
    public function setCurrentUser($currentUser)
    {
        $this->currentUser = $currentUser;
    }

    /**
     * @param CommentInterface $comment
     * @param int              $value
     *
     * @return bool
     */
    public function addVote($comment, $value = 1)
    {
        if ($this->userHasVoted($comment)) {
            return false;
        }

        $vote = new CommentVote();
        $vote->setUserId($this->getCurrentUser()->getId());
        $vote->setValue($value);

        $comment->addVote($vote);
        if ($value > 0) {
            $comment->setUpvotesCount($comment->getUpvotesCount() + $value);
        } else {
            $comment->setDownvotesCount($comment->getDownvotesCount() + abs($value));
        }

        $this->om->flush();

        return $comment->getUpvotesCount() + $comment->getDownvotesCount();
    }

    /**
     * @param CommentInterface $comment
     *
     * @return bool|null
     */
    public function userHasVoted(CommentInterface $comment)
    {
        if (!$this->currentUser) {
            return null;
        }

        $user = $this->getCurrentUser();
        $vote = $this->om->getRepository(Comment::class)->findOneBy([
            '_id'            => new \MongoId($comment->getId()),
            'votes . userId' => new \MongoId($user->getId()),
        ]);

        return !empty($vote);
    }

    /**
     * @param CommentInterface $comment
     *
     * @return CommentInterface
     */
    public function removeVote(CommentInterface $comment)
    {
        $user      = $this->getCurrentUser();
        $commentId = new \MongoId($comment->getId());

        $vote = $this->getOm()->getDocumentCollection(Comment::class)->findOne(
            [
                '_id'            => $commentId,
                'votes . userId' => new \MongoId($user->getId()),
            ],
            ['votes . $']
        );

        if ($vote) {
            $result = $this->getOm()->getDocumentCollection(Comment::class)->update(
                [
                    '_id' => $commentId,
                ],
                [
                    '$pull' => ['votes' => ['userId' => new \MongoId($user->getId())]],
                ]
            );

            if ($result['nModified']) {
                if ($vote['votes'][0]['value'] > 0) {
                    $field = 'upvotesCount';
                    $comment->setUpvotesCount($comment->getUpvotesCount() - 1);
                } else {
                    $field = 'downvotesCount';
                    $comment->setDownvotesCount($comment->getDownvotesCount() - 1);
                }

                $this->getOm()->getDocumentCollection(Comment::class)->update(
                    ['_id' => $commentId],
                    ['$inc' => [$field => -1]]
                );
            }
        }

        return $comment;
    }

    /**
     * @param CommentInterface $comment
     *
     * @return bool
     */
    public function upvote($comment)
    {
        return $this->addVote($comment, 1);
    }

    /**
     * @param CommentInterface $comment
     *
     * @return bool
     */
    public function downvote($comment)
    {
        return $this->addVote($comment, -1);
    }

    /**
     * @param CommentInterface $comment
     */
    public function deleteComment($comment)
    {
        /** @var Comment[] $comments */
        $comments = $this->om
            ->getRepository(Comment::class)
            ->findBy(['slug' => new \MongoRegex('/^' . $comment->getSlug() . '/')]);

        foreach ($comments as $item) {
            $this->om->remove($item);

            $this->om->flush($item);
            $this->eventDispatcher->dispatch('comments_bundle.comment.delete', new DeleteCommentEvent($item));
        }
    }

    /**
     * Initiate current user value
     */
    public function initiateCurrentUser()
    {
        if (($token = $this->tokenStorage->getToken()) && ($user = $token->getUser()) && is_object($user)) {
            $this->currentUser = $user;
        }
    }

    /**
     * @param array $args
     *
     * @return CommentInterface[]
     */
    public function getCursoredComments($args)
    {
        return $this->om->getRepository(Comment::class)->getCursoredList($args, !empty($args['filters']) ? $args['filters'] : []);
    }

    /**
     * @param CommentableInterface $object
     *
     * @return CommentInterface[]
     */
    public function getComments(CommentableInterface $object)
    {
        /** @var DocumentManager $om */
        $om = $this->om;

        return $om->getRepository(Comment::class)
            ->createQueryBuilder()
            ->field('modelId')->equals(new \MongoId($object->getId()))
            ->sort('slug')
            ->getQuery()
            ->execute();
    }

    /**
     * @return bool
     */
    public function isAllowAnonymous()
    {
        return $this->allowAnonymous;
    }

    /**
     * @param bool $allowAnonymous
     */
    public function setAllowAnonymous($allowAnonymous)
    {
        $this->allowAnonymous = $allowAnonymous;
    }

    /**
     * @return DocumentManager
     */
    public function getOm()
    {
        return $this->om;
    }

    /**
     * @return int
     */
    public function getMaxDepth()
    {
        return $this->maxDepth;
    }

    /**
     * @param int $maxDepth
     */
    public function setMaxDepth($maxDepth)
    {
        $this->maxDepth = $maxDepth;
    }

    protected function processAuth(CommentInterface $comment)
    {
        $user          = $this->getCurrentUser();
        $userReference = new UserReference($user);

        $comment->setUserReference($userReference);
    }
}
