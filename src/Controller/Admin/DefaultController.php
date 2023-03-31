<?php
namespace App\Controller\Admin;

use App\Service\AdminService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
class DefaultController extends AbstractController
{
    public function __construct(
        private AdminService $service
    ){}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index():Response 
    {
        return $this->render('admin/index.html.twig', $this->service->getData());
    }

}