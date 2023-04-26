<?php 

namespace App\Controller\Auth;

use App\Entity\UserToken;
use App\Service\UserService;
use App\Form\Auth\ResetPasswordType;
use App\Form\Auth\ForgotPasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/reinitialiser', name: 'reset_form', methods: ['GET', 'POST'])]
    public function reset(Request $request):Response
    {
        $token = $this->service->getUserToken($request->query->get('token'));
        if (is_null($token)) {
            return $this->renderForm('auth/forgot-password/invalid_token.html.twig');
        }
        
        if ($token instanceof UserToken && $this->service->checkUserToken($token) === false) {
            return $this->renderForm('auth/forgot-password/invalid_token.html.twig');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $success = $this->service->resetPassword($token, $form->get('password')->getData());

            if ($success) {
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->renderForm('auth/forgot-password/index.html.twig', compact('form'));
    }
    
}