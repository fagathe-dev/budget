<?php

namespace App\Security\Voter;

use App\Entity\Budget;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class BudgetVoter extends Voter
{
    public const BUDGET_EDIT = 'BUDGET_EDIT';
    public const BUDGET_VIEW = 'BUDGET_VIEW';
    public const BUDGET_DELETE = 'BUDGET_DELETE';

    public function __construct(
        private Security $security
    ) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        // https://symfony.com/doc/current/security/voters.html
        return in_array(
                $attribute, 
                [self::BUDGET_EDIT, self::BUDGET_VIEW]
            )
            && $subject instanceof Budget;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::BUDGET_EDIT:
                return $this->security->isGranted('ROLE_ADMIN') || $subject->getUser() === $user;
            case self::BUDGET_VIEW:
                return $this->security->isGranted('ROLE_ADMIN') || $subject->getUser() === $user;
            case self::BUDGET_DELETE:
                return $this->security->isGranted('ROLE_ADMIN') || $subject->getUser() === $user;
        }

        return false;
    }
}
