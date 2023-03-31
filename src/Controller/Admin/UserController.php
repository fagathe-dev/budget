<?php 
namespace App\Controller\Admin;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user', name: 'admin_user_')]
class UserController extends AbstractController
{
    
    public function __construct(
        private UserService $service
    ){}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index():Response 
    {
        return $this->render('admin/user/index.html.twig');
    }

}
