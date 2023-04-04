<?php
namespace App\Controller\Api;

use App\Service\ApiService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/dashboard', name: 'api_dashboard_')]
class DashboardController extends AbstractController 
{

    public function __construct(
        private ApiService $apiService
    ){}


    #[Route('', name: 'index')]
    public function index():JsonResponse
    {
        dd($this->apiService->getData());
        return $this->json([]);
    }

}