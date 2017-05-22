<?php

namespace Youshido\CommentsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class CommentVote
 *
 * @ODM\EmbeddedDocument()
 */
class CommentVote
{
    /** @ODM\ObjectId() */
    private $userId;

    /** @ODM\Field(type="int") */
    private $value;

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
