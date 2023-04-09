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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class ApiService 
{

    use ServiceTrait;

    private $session;

    public function __construct(
        private CategoryRepository $categoryRepository,
        private ExpenseRepository $expenseRepository,
        private BudgetRepository $budgetRepository,
        private EntityManagerInterface $manager,
        private Security $security, 
        private ValidatorInterface $validator,
        private SerializerInterface $serializer
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
     * editExpense
     *
     * @param  mixed $request
     * @return object
     */
    public function createExpense (Request $request):object {
        $data = $request->getContent();
        $expense = $this->serializer->deserialize($data, Expense::class, 'json');
        $data = json_decode($data, true);

        $category = array_key_exists('category', $data) && $data['category'] !== null 
            ? 
            $this->categoryRepository->find((int) $data['category']) 
            : 
            $this->categoryRepository->findOneBy(['slug' => 'autres']);

        $expense->setCreatedAt($this->now())
            ->setCategory($category)
            ->setUser($this->security->getUser())    
        ;

        if ($expense->isIsPaid()) {
            $expense->setPaidAt($this->now());
        }

        $this->manager->persist($expense);
        $this->manager->flush();

        return $this->sendJson($expense, Response::HTTP_CREATED);
    } 
    
    /**
     * editExpense
     *
     * @param  mixed $request
     * @return object
     */
    public function editExpense (Expense $expense, Request $request):object {
        $data = $request->getContent();
        $expense = $this->serializer->deserialize($data, Expense::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $expense
        ]);
        $data = json_decode($data, true);

        $category = array_key_exists('category', $data) && $data['category'] !== null 
            ? 
            $this->categoryRepository->find((int) $data['category']) 
            : 
            $this->categoryRepository->findOneBy(['slug' => 'autres']);

        $expense->setCreatedAt($this->now())
            ->setCategory($category)
            ->setUser($this->security->getUser())    
        ;

        if ($expense->isIsPaid()) {
            $expense->setPaidAt($this->now());
        }

        $this->manager->persist($expense);
        $this->manager->flush();

        return $this->sendJson($expense, Response::HTTP_OK);
    } 

} 