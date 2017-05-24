<?php

namespace Youshido\CommentsBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Comment
 *
 * @ODM\Document(collection="comments", repositoryClass="Youshido\CommentsBundle\Document\Repository\CommentRepository")
 */
class Comment implements CommentInterface
{
    /** @ODM\Id() */
    private $id;

    /** @ODM\Field() */
    private $content;

    /** @ODM\Field(type="object_id") */
    private $parentId;

    /** @ODM\Date() */
    private $createdAt;

    /** @ODM\Field(type="int") */
    private $status = CommentStatus::ACTIVE;

    /** @ODM\EmbedMany(targetDocument="CommentVote") */
    private $votes;

    /** @ODM\Field(type="int") */
    private $upvotesCount;

    /** @ODM\Field(type="int") */
    private $downvotesCount;

    /** @ODM\EmbedOne(targetDocument="UserReference") */
    private $userReference;

    /** @ODM\Field(type="int") */
    private $level = 0;

    /** @ODM\Field(type="object_id") */
    private $modelId;

    /** @ODM\Field() */
    private $slug;

    /** @ODM\Field(type="int") */
    private $repliesCount = 0;

    /** @ODM\Field(type="int") */
    private $popularRating = 0;

    /**
     * Comment constructor.
     *
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
        $this->votes   = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return Comment
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     *
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param mixed $parentId
     *
     * @return Comment
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     *
     * @return Comment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     *
     * @return Comment
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * @param mixed $votes
     *
     * @return Comment
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * @param CommentVote $vote
     */
    public function addVote($vote)
    {
        $this->votes[] = $vote;
    }

    /**
     * @param CommentVote $vote
     */
    public function removeVote($vote)
    {
        $this->votes->removeElement($vote);
    }

    /**
     * @return mixed
     */
    public function getUpvotesCount()
    {
        return $this->upvotesCount;
    }

    /**
     * @param mixed $upvotesCount
     *
     * @return Comment
     */
    public function setUpvotesCount($upvotesCount)
    {
        $this->upvotesCount = $upvotesCount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDownvotesCount()
    {
        return $this->downvotesCount;
    }

    /**
     * @param mixed $downvotesCount
     *
     * @return Comment
     */
    public function setDownvotesCount($downvotesCount)
    {
        $this->downvotesCount = $downvotesCount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return UserReference
     */
    public function getUserReference()
    {
        return $this->userReference;
    }

    /**
     * @param UserReference $userReference
     *
     * @return Comment
     */
    public function setUserReference($userReference)
    {
        $this->userReference = $userReference;

        return $this;
    }

    /**
     * @return string
     */
    public function getModelId()
    {
        return $this->modelId;
    }

    /**
     * @param string $modelId
     *
     * @return Comment
     */
    public function setModelId($modelId)
    {
        $this->modelId = $modelId;

        return $this;
    }

    /**
     * @return UserReference
     */
    public function getAuthor()
    {
        return $this->userReference;
    }

    /**
     * @return int
     */
    public function getRepliesCount()
    {
        return $this->repliesCount;
    }

    /**
     * @param int $repliesCount
     *
     * @return Comment
     */
    public function setRepliesCount($repliesCount)
    {
        $this->repliesCount = $repliesCount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPopularRating()
    {
        return $this->popularRating;
    }

    /**
     * @param mixed $popularRating
     *
     * @return Comment
     */
    public function setPopularRating($popularRating)
    {
        $this->popularRating = $popularRating;

        return $this;
    }
}
