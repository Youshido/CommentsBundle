<?php
/**
 * This file is a part of Youshido CommentsBundle.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 4/27/17 7:50 PM
 */

namespace Youshido\CommentsBundle\Document;


interface CommentUserInterface
{

    public function getId();

    /** @return EmbeddedPath */
    public function getAvatar();

}