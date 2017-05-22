<?php

namespace Youshido\CommentsBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Youshido\CommentsBundle\Document\CommentInterface;

/**
 * Class AbstractCommentEvent
 */
class AbstractCommentEvent extends Event
{
    /**
     * @var CommentInterface
     */
    private $comment;

    /**
     * AbstractCommentEvent constructor.
     *
     * @param CommentInterface $comment
     */
    public function __construct(CommentInterface $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return CommentInterface
     */
    public function getComment()
    {
        return $this->comment;
    }
}
