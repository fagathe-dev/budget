<?php 
namespace App\Controller\Auth;

use App\Breadcrumb\Breadcrumb;
use App\Form\Auth\AccountType;
use App\Service\AccountService;
use App\Breadcrumb\BreadcrumbItem;
use App\Form\Auth\ChangeEmailType;
use App\Form\Auth\ChangePasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/mon-compte', name: 'app_account_')]
class AccountController extends AbstractController 
{
    
    public function __construct(
        private AccountService $service
    ){}

    #[Route('', name: 'index', methods: ['GET', 'POST'])]
    public function index(Request $request):Response
    {

        $user = $this->getUser();

        $breadcrumb = new Breadcrumb([
            new BreadcrumbItem('Mon compte')
        ]);

        $formInfo = $this->createForm(AccountType::class, $user);
        $formInfo->handleRequest($request);

        if($formInfo->isSubmitted() && $formInfo->isValid()) {
            $this->service->save($user);

            return $this->redirectToRoute('app_account_index');
        }

        $formPassword = $this->createForm(ChangePasswordType::class);
        $formPassword->handleRequest($request);

        if($formPassword->isSubmitted() && $formPassword->isValid()) {
            $this->service->updatePassword($formPassword->get('password')->getData());

            return $this->redirectToRoute('app_account_index');
        }

        $formEmail = $this->createForm(ChangeEmailType::class);
        $formEmail->handleRequest($request);

        if($formEmail->isSubmitted() && $formEmail->isValid()) {
            $this->service->emailVerify($formEmail->get('email')->getData());

            return $this->redirectToRoute('app_account_index');
        }

        return $this->renderForm('auth/account/index.html.twig', compact(
            'formInfo', 
            'formPassword', 
            'formEmail', 
            'user', 
            'breadcrumb'
        ));
    }

    #[Route('/verification-email', name: 'verify_email', methods: ['get'])]
    public function verifyEmail(Request $request):Response 
    {
        $this->service->verifyEmail($request->query->get('token'));

        return $this->redirectToRoute('app_default');
    }

    #[Route('/uploadImage', name: 'upload_image', methods: ['POST'])]
    public function uploadImage(Request $request):JsonResponse 
    {
        $response = $this->service->uploadImage($request);
        
        return $this->json(
            $response->data,
            $response->status,
            $response->headers,
            [
                'groups' => ['api_upload'],
            ]
        );
    }

}