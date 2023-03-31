<?php 
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category', name: 'admin_category_')]
class CategoryController extends AbstractController
{
    
    public function __construct(
        // private UserService $service
    ){}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index():Response 
    {
        return $this->render('admin/category/index.html.twig', );
    }

}
