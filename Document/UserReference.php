<?php

namespace Youshido\CommentsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class UserReference
 *
 * @ODM\EmbeddedDocument()
 */
class UserReference
{
    /** @ODM\Field() @var string */
    private $name;

    /** @ODM\EmbedOne(targetDocument="EmbeddedPath") @var EmbeddedPath */
    private $avatar;

    /** @ODM\Field(type="object_id") */
    private $userId;

    /**
     * UserReference constructor.
     *
     * @param CommentUserInterface $user
     */
    public function __construct(CommentUserInterface $user)
    {
        $this->userId = $user->getId();
        $this->name   = (string) $user;

        if ($user->getAvatar()) {
            $this->avatar = new EmbeddedPath();
            $this->avatar->setPath($user->getAvatar()->getPath());
        }
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

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
     * @return EmbeddedPath
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param EmbeddedPath $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }
}
