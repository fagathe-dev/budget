<?php 
namespace App\Controller\Auth;

use App\Breadcrumb\Breadcrumb;
use App\Form\Auth\AccountType;
use App\Service\AccountService;
use App\Breadcrumb\BreadcrumbItem;
use App\Form\Auth\ChangePasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

        return $this->renderForm('auth/account/index.html.twig', compact('formInfo', 'formPassword', 'user', 'breadcrumb'));
    }
}