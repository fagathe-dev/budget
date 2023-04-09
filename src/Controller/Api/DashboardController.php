<?php
namespace App\Controller\Api;

use App\Entity\Expense;
use App\Service\ApiService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/dashboard', name: 'api_dashboard_')]
class DashboardController extends AbstractController 
{

    public function __construct(
        private ApiService $apiService
    ){}

    #[Route('/expense', name: 'create_expense', methods: ['POST'])]
    public function createExpense(Request $request):JsonResponse
    {
        $response = $this->apiService->createExpense($request);

        return $this->json(
            $response->data,
            $response->status,
            $response->headers,
            [
                'groups' => ['api_dashboard'],
            ]
        );
    }

    #[Route('/expense/{id}', name: 'edit_expense', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function editExpense(Expense $expense, Request $request):JsonResponse
    {
        $response = $this->apiService->editExpense($expense, $request);

        return $this->json(
            $response->data,
            $response->status,
            $response->headers,
            [
                'groups' => ['api_dashboard'],
            ]
        );
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index():JsonResponse
    {
        $response = $this->apiService->getData();
        return $this->json(
            $response->data,
            $response->status,
            $response->headers,
            [
                'groups' => ['api_dashboard'],
            ]
        );
    }

}