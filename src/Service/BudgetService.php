<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Budget;
use App\Utils\ServiceTrait;
use App\Repository\BudgetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

final class BudgetService
{

    use ServiceTrait;

    public function __construct(
        private EntityManagerInterface $manager,
        private BudgetRepository $repository, 
        private Security $security
    ) {}

    public function save(Budget $budget):void
    {
        if (!$budget->getUser() instanceof User) {
            $budget->setUser($this->security->getUser());
        }

        $this->repository->save($budget, true);
    }

}