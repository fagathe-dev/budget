<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Budget;
use App\Utils\ServiceTrait;
use App\Repository\BudgetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

final class BudgetService
{

    use ServiceTrait;

    public function __construct(
        private EntityManagerInterface $manager,
        private BudgetRepository $repository, 
        private Security $security,
        private PaginatorInterface $paginator
    ) {}
    
    /**
     * save
     *
     * @param  mixed $budget
     * @return void
     */
    public function save(Budget $budget):void
    {
        if (!$budget->getUser() instanceof User) {
            $budget->setUser($this->security->getUser());
        }

        $this->repository->save($budget, true);
    }
    
    /**
     * index
     *
     * @param  mixed $request
     * @return array
     */
    public function index(Request $request):array 
    {

        $user = $this->security->getUser();

        $paginatedBudgets = $this->paginator->paginate(
            $user->getBudgets(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->getInt('nbItems', 10) /*limit per page*/
        );

        return compact('paginatedBudgets');
    }

}