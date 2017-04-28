<?php

namespace Youshido\CommentsBundle\Security\Voter;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Youshido\CommentsBundle\Document\CommentInterface;


class CommentVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof CommentInterface) {
            return false;
        }

        return true;
    }

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

    private function canEdit(CommentInterface $comment, $user)
    {
        if ($comment->getUserReference()->getUserId() === $user->getId()) {
            return true;
        }

        return false;
    }

    private function canDelete(CommentInterface $comment, $user)
    {
        return $this->canEdit($comment, $user);
    }
}