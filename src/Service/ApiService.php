<?php
namespace App\Service;

use App\Entity\Expense;
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
     * @return object
     */
    public function getData():object 
    {
        $expenses = $this->security->getUser()->getExpenses()->toArray();
        $budgets = $this->security->getUser()->getBudgets();
        $categories = $this->categoryRepository->findAll();

        $paid = array_filter($expenses, function(Expense $v) {
            return $v->isIsPaid() && $v->getPaidAt()->format('m-Y') === ($this->now())->format('m-Y');
        });
        $unPaid = array_filter($expenses, function(Expense $v) {
            return !$v->isIsPaid();
        });
        usort($unPaid, function($a, $b) {
            return $a->getCreatedAt() < $b->getCreatedAt();
        });

        return $this->sendJson(compact('expenses', 'budgets', 'categories', 'paid', 'unPaid'));
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