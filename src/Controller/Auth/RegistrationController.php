<?php 
namespace App\Controller\Auth;

use App\Entity\User;
use App\Form\Auth\RegistrationType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/inscription', name: 'app_registration_')]
class RegistrationController extends AbstractController
{

    public function __construct(
        private UserService $service
    ){}

    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function incription(Request $request):Response
    {
        $user = new User;
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        $success = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->create($user->setRoles(['ROLE_ADMIN']), true);
            $this->addFlash('success', 'Vous vous êtes avec succès 🚀');

            return $this->redirectToRoute('app_login');
        }

        return $this->renderForm('auth/register/index.html.twig', compact('form'));
    }

}