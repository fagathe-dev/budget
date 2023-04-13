<?php 
namespace App\Controller\Admin;

use App\Entity\User;
use App\Service\UserService;
use App\Form\Admin\EditUserType;
use App\Form\Admin\CreateUserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/user', name: 'admin_user_')]
class UserController extends AbstractController
{
    
    public function __construct(
        private UserService $service
    ){}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request):Response 
    {
        return $this->render('admin/user/index.html.twig', $this->service->index($request));
    }

    #[Route('/new', name: 'new', methods: ['POST', 'GET'])]
    public function newUser(Request $request):Response 
    {
        $user = new User;
        $form = $this->createForm(CreateUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->create($user);

            $this->addFlash('success', 'Utilisateur enregistré.');
            return $this->redirectToRoute('admin_user_index');
        }

        return $this->renderForm('admin/user/new.html.twig', compact('form', 'user'));
    } 


    #[Route('/{id}', name: 'edit', methods: ['POST', 'GET'], requirements: ['id' => '\d+']) ]
    public function editUser(User $user, Request $request):Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->save($user);

            $this->addFlash('info', 'Utilisateur enregistré.');
            return $this->redirectToRoute('admin_user_edit', [
                'id' => $user->getId()
            ]);
        }

        return $this->renderForm('admin/user/edit.html.twig', compact('user', 'form'));
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(User $user):JsonResponse 
    {
        $response = $this->service->delete($user);

        return $this->json(
            $response->data,
            $response->status,
            $response->headers
        );
    }

}
