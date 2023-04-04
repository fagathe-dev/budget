<?php
namespace App\Service;

use App\Utils\ServiceTrait;
use App\Repository\BudgetRepository;
use App\Repository\ExpenseRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;

final class ApiService 
{

    use ServiceTrait;

    private $session;

    public function __construct(
        private CategoryRepository $categoryRepository,
        private ExpenseRepository $expenseRepository,
        private BudgetRepository $budgetRepository,
        private EntityManagerInterface $manager,
        private Security $security
    ) {
        $this->session = new Session;
    }
    
    /**
     * getData
     *
     * @return array
     */
    public function getData():array 
    {
        $expenses = $this->security->getUser()->getExpenses();
        $budgets = $this->security->getUser()->getBudgets();
        $categories = $this->categoryRepository->findAll();

        dd(count($this->expenseRepository->findUserLatestExpenses()));

        return compact('expenses', 'budgets');
    }
    
    /**
     * saveExpense
     *
     * @param  mixed $request
     * @return object
     */
    public function saveExpense (Request $request):object {
        return $this->sendJson();
    } 

} 