<?php 
namespace App\Controller\Admin;

use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category', name: 'admin_category_')]
class CategoryController extends AbstractController
{
    
    public function __construct(
        private CategoryService $service
    ){}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request):Response 
    {
        return $this->render('admin/category/index.html.twig', $this->service->index($request));
    }

}
