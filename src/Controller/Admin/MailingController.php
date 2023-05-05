<?php 
namespace App\Controller\Admin;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/email', name: 'admin_mailing_')]
class MailingController extends AbstractController
{

    public function __construct() {}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index():Response 
    {
        return $this->render('');
    }

    #[Route('/view', name: 'show', methods: ['GET'])]
    public function show():Response 
    {
        return $this->render('');
    }

}