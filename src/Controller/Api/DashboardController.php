<?php
namespace App\Controller\Api;

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

    #[Route('', name: 'create_expense', methods: ['POST'])]
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