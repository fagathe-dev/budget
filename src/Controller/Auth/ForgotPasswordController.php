<?php 

namespace App\Controller\Auth;

use App\Form\Auth\ForgotPasswordType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mot-de-passe-oublie', name:'app_forgot_password_')]
class ForgotPasswordController extends AbstractController
{

    public function __construct(
        private UserService $service
    ){}

    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function index(Request $request):Response
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->sendCreateForgotPasswordMail($form->get('email')->getData());
        }

        return $this->renderForm('auth/forgot-password/index.html.twig', compact('form'));
    }
    
}