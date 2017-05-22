<?php

namespace Youshido\CommentsBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Youshido\CommentsBundle\Document\CommentInterface;

/**
 * Class CommentVoter
 */
class CommentVoter extends Voter
{
    const EDIT   = 'edit';
    const DELETE = 'delete';

    /** @var AccessDecisionManagerInterface */
    protected $decisionManager;

    /**
     * CommentVoter constructor.
     *
     * @param AccessDecisionManagerInterface $decisionManager
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE], false)) {
            return false;
        }

        if (!$subject instanceof CommentInterface) {
            return false;
        }

        return true;
    }

    /**
     * @param string         $attribute
     * @param mixed          $subject
     * @param TokenInterface $token
     *
     * @return bool
     * @throws \LogicException
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!is_object($user)) {
            return false;
        }

        if ($this->decisionManager->decide($token, ['ROLE_SUPER_ADMIN'])) {
            return true;
        }

        /** @var CommentInterface $comment */
        $comment = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($comment, $user);

            case self::DELETE:
                return $this->canDelete($comment, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param CommentInterface $comment
     * @param                  $user
     *
     * @return bool
     */
    private function canEdit(CommentInterface $comment, $user)
    {
        return $comment->getUserReference()->getUserId() === $user->getId();
    }

    /**
     * @param CommentInterface $comment
     * @param UserInterface    $user
     *
     * @return bool
     */
    private function canDelete(CommentInterface $comment, $user)
    {
        return $this->canEdit($comment, $user);
    }
}
