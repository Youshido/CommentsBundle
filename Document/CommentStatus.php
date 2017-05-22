<?php

namespace Youshido\CommentsBundle\Document;

/**
 * Class CommentStatus
 */
class CommentStatus
{
    const ACTIVE  = 1;
    const PENDING = 2;
    const BLOCKED = 4;
    const SPAM    = 8;
    const DELETED = 16;
}
