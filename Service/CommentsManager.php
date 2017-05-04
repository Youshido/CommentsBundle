<?php
/**
 * This file is a part of Youshido CommentsBundle.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 3/15/17 7:25 PM
 */

namespace Youshido\CommentsBundle\Service;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Youshido\CommentsBundle\Document\Comment;
use Youshido\CommentsBundle\Document\CommentableInterface;
use Youshido\CommentsBundle\Document\CommentInterface;
use Youshido\CommentsBundle\Document\CommentVote;
use Youshido\CommentsBundle\Document\UserReference;

class CommentsManager
{

    /** @var ObjectManager */
    private $om;

    /** @var TokenStorage */
    private $tokenStorage;

    /** @var */
    private $currentUser;

    /** @var bool */
    private $allowAnonymous = false;

    /**
     * CommentsManager constructor.
     * @param ObjectManager $om
     * @param TokenStorage  $tokenStorage
     */
    public function __construct(ObjectManager $om, TokenStorage $tokenStorage)
    {
        $this->om           = $om;
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * @param CommentableInterface $object
     * @param string               $content
     * @param                      $parentId
     * @return CommentInterface
     */
    public function createComment($object, $content, $parentId = null)
    {
        $comment = new Comment($content);
        $comment->setModelId(new \MongoId($object->getId()));
        $parentSlug = '';
        if (!empty($parentId)) {
            $comment->setParentId($parentId);
            $parent     = $this->om->getRepository(Comment::class)->find($parentId);
            $parentSlug = $parent->getSlug() . '-';
            $comment->setLevel($parent->getLevel() + 1);
        }
        $comment->setCreatedAt(new \DateTime());
        $comment->setSlug($parentSlug . (new \DateTime())->format("Y.m.d.H.i.s-") . uniqid());
        $this->processAuth($comment);

        $this->om->persist($comment);
        $this->om->flush();

        return $comment;
    }

    protected function processAuth(CommentInterface $comment)
    {
        $user          = $this->getCurrentUser();
        $userReference = new UserReference($user);
        $comment->setUserReference($userReference);
    }

    public function getCurrentUser()
    {
        if (!$this->allowAnonymous && !$this->currentUser) {
            throw new \Exception('Anonymous comments are not allowed');
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
     * @param                  $value
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

    public function userHasVoted(CommentInterface $comment)
    {
        if (!$this->currentUser) return null;
        $user = $this->getCurrentUser();
        /** @var DocumentManager $om */
        $om   = $this->om;
        $vote = $om->getRepository(Comment::class)->findOneBy([
            '_id'          => $comment->getId(),
            'votes.userId' => new \MongoId($user->getId()),
        ]);

        return !empty($vote);
    }

    public function removeVote(CommentInterface $comment)
    {
        $user = $this->getCurrentUser();
        /** @var DocumentManager $om */
        $om   = $this->om;
        $om->getDocumentCollection(Comment::class)->update(
            ['_id' => $comment->getId(),],
            ['$pull' => ['votes.userId' => new \MongoId($user->getId())],
            ]);
        return $comment;
    }

    /**
     * @param CommentInterface $comment
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
        /** @var DocumentManager $om */
        $om = $this->om;
        $om->getDocumentCollection(Comment::class)->createQueryBuilder()->remove()
            ->field('slug')->equals(new \MongoRegex('/^' . $comment->getSlug() . '/'))
            ->getQuery()->execute();
    }

    public function initiateCurrentUser()
    {
        if ($token = $this->tokenStorage->getToken()) {
            if (($user = $token->getUser()) && is_object($user)) {
                $this->currentUser = $user;
            }
        }
    }

    /**
     * @return CommentInterface[]
     */
    public function getCursoredComments($args)
    {
        return $this->om->getRepository(Comment::class)->getCursoredList($args, !empty($args['filters']) ? $args['filters'] : []);
    }

    /**
     * @param CommentableInterface $object
     * @return CommentInterface[]
     */
    public function getComments(CommentableInterface $object)
    {
        /** @var DocumentManager $om */
        $om = $this->om;

        return $om->getRepository(Comment::class)->createQueryBuilder()
            ->field('modelId')->equals(new \MongoId($object->getId()))
            ->sort('slug')
            ->getQuery()->execute();
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


}