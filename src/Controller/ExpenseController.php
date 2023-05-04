<?php
namespace App\Controller;

use App\Entity\Expense;
use App\Repository\ExpenseRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/expense', name: 'app_expense')]
final class ExpenseController extends AbstractController
{
    
    public function __construct(
        private ExpenseRepository $repository
    ) {}

    #[Route('/{id}/delete', name: 'delete', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function delete(Expense $expense):RedirectResponse
    {
        $this->repository->remove($expense, true);

        return $this->redirectToRoute('app_default');
    }

}