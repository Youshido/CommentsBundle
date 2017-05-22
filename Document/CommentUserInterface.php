<?php

namespace Youshido\CommentsBundle\Document;

/**
 * Interface CommentUserInterface
 */
interface CommentUserInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return EmbeddedPath
     */
    public function getAvatar();
}
