<?php 
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{

    public function __construct(){}

    #[Route('/', name: 'app_default')] 
    public function default():Response 
    {
        return $this->render('default/index.html.twig');
    }
}