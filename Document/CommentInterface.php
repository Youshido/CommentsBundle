<?php

namespace Youshido\CommentsBundle\Document;

/**
 * Class Comment
 */
interface CommentInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param mixed $id
     *
     * @return CommentInterface
     */
    public function setId($id);

    /**
     * @return mixed
     */
    public function getContent();

    /**
     * @param mixed $content
     *
     * @return CommentInterface
     */
    public function setContent($content);

    /**
     * @return mixed
     */
    public function getParentId();

    /**
     * @param mixed $parentId
     *
     * @return CommentInterface
     */
    public function setParentId($parentId);

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @param mixed $createdAt
     *
     * @return CommentInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * @return mixed
     */
    public function getStatus();

    /**
     * @param mixed $status
     *
     * @return CommentInterface
     */
    public function setStatus($status);

    /**
     * @return mixed
     */
    public function getVotes();

    /**
     * @param mixed $votes
     *
     * @return CommentInterface
     */
    public function setVotes($votes);

    /**
     * @return mixed
     */
    public function getUpvotesCount();

    /**
     * @param mixed $upvotesCount
     *
     * @return CommentInterface
     */
    public function setUpvotesCount($upvotesCount);

    /**
     * @return mixed
     */
    public function getDownvotesCount();

    /**
     * @param mixed $downvotesCount
     *
     * @return CommentInterface
     */
    public function setDownvotesCount($downvotesCount);

    /**
     * @return mixed
     */
    public function getUserReference();

    /**
     * @param mixed $vote
     *
     * @return mixed
     */
    public function addVote($vote);

    /**
     * @param mixed $vote
     *
     * @return mixed
     */
    public function removeVote($vote);

    /**
     * @return string
     */
    public function getSlug();

    /**
     * @param mixed $userReference
     *
     * @return CommentInterface
     */
    public function setUserReference($userReference);

    /**
     * @return mixed
     */
    public function getModelId();

    /**
     * @param mixed $modelId
     *
     * @return CommentInterface
     */
    public function setModelId($modelId);

    /**
     * @return int
     */
    public function getPopularRating();

    /**
     * @param int $popularRating
     *
     * @return CommentInterface
     */
    public function setPopularRating($popularRating);

    /**
     * @return int
     */
    public function getRepliesCount();

    /**
     * @param int $repliesCount
     *
     * @return CommentInterface
     */
    public function setRepliesCount($repliesCount);
}
