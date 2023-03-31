<?php 
namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\CreateUserType;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/admin/user', name: 'admin_user_')]
class UserController extends AbstractController
{
    
    public function __construct(
        private UserService $service
    ){}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index():Response 
    {
        return $this->render('admin/user/index.html.twig', $this->service->index());
    }

    #[Route('/new', name: 'new', methods: ['POST', 'GET'])]
    public function newUser(Request $request):Response 
    {
        $user = new User;
        $form = $this->createForm(CreateUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->save($user);

            $this->addFlash('success', 'Utilisateur enregistré.');
            return $this->redirectToRoute('admin_user_index');
        }

        return $this->renderForm('admin/user/edit.html.twig', compact('form', 'user'));
    } 


    #[Route('/{id}', name: 'edit', methods: ['POST', 'GET'], requirements: ['id' => '\d+']) ]
    public function editUser(User $user, Request $request):Response
    {
        return $this->renderForm('admin/user/edit.html.twig', compact('user', 'form'));
    }

}
