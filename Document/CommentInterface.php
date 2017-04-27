<?php
/**
 * This file is a part of Youshido CommentsBundle.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 3/15/17 8:24 PM
 */

namespace Youshido\CommentsBundle\Document;


/**
 * Class Comment
 * @package Youshido\CommentsBundle\Document
 * @ODM\Document(collection="comments")
 */
interface CommentInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param mixed $id
     * @return Comment
     */
    public function setId($id);

    /**
     * @return mixed
     */
    public function getContent();

    /**
     * @param mixed $content
     * @return Comment
     */
    public function setContent($content);

    /**
     * @return mixed
     */
    public function getParentId();

    /**
     * @param mixed $parentId
     * @return Comment
     */
    public function setParentId($parentId);

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @param mixed $createdAt
     * @return Comment
     */
    public function setCreatedAt($createdAt);

    /**
     * @return mixed
     */
    public function getStatus();

    /**
     * @param mixed $status
     * @return Comment
     */
    public function setStatus($status);

    /**
     * @return mixed
     */
    public function getVotes();

    /**
     * @param mixed $votes
     * @return Comment
     */
    public function setVotes($votes);

    /**
     * @return mixed
     */
    public function getUpvotesCount();

    /**
     * @param mixed $upvotesCount
     * @return Comment
     */
    public function setUpvotesCount($upvotesCount);

    /**
     * @return mixed
     */
    public function getDownvotesCount();

    /**
     * @param mixed $downvotesCount
     * @return Comment
     */
    public function setDownvotesCount($downvotesCount);

    /**
     * @return mixed
     */
    public function getUserReference();

    public function addVote($vote);

    public function removeVote($vote);

    public function getSlug();

    /**
     * @param mixed $userReference
     * @return Comment
     */
    public function setUserReference($userReference);

    /**
     * @return mixed
     */
    public function getModelId();

    /**
     * @param mixed $modelId
     * @return Comment
     */
    public function setModelId($modelId);
}