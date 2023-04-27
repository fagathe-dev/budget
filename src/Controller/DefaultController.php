<?php 
namespace App\Controller;

use App\Mailer\Auth\WelcomeMail;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{

    public function __construct(){}

    #[Route('/', name: 'app_default')] 
    public function default(WelcomeMail $mailer):Response 
    {
        $mailer->confirmRegistration($this->getUser());
        return $this->render('default/index.html.twig');
    }
}