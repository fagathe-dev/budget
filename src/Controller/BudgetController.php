<?php
namespace App\Controller;

use App\Entity\Budget;
use App\Form\BudgetType;
use App\Breadcrumb\Breadcrumb;
use App\Service\BudgetService;
use App\Breadcrumb\BreadcrumbItem;
use App\Security\Voter\BudgetVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/budget', name: 'app_budget_')]
class BudgetController extends AbstractController 
{

    public function __construct(
        private BudgetService $service
    ){}

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(Budget $budget):JsonResponse
    {
        $this->denyAccessUnlessGranted(BudgetVoter::BUDGET_EDIT, $budget);
        $response = $this->service->delete($budget);
        
        return $this->json(
            $response->data,
            $response->status,
            $response->headers
        );
    }

    #[Route('/{id}', name: 'edit', methods: ['POST', 'GET'], requirements: ['id' => '\d+'])]
    public function edit(Budget $budget, Request $request):Response
    {
        $breadcrumb = new Breadcrumb([
            new BreadcrumbItem('Mes budgets', $this->generateUrl('app_budget_index')),
            new BreadcrumbItem('Modifier le budget ' . $budget->getCategory()->getName())
        ]);

        $this->denyAccessUnlessGranted(BudgetVoter::BUDGET_EDIT, $budget);
        $form = $this->createForm(BudgetType::class, $budget);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->save($budget);
        }

        return $this->renderForm('budget/edit.html.twig', compact('form', 'budget', 'breadcrumb'));
    }

    #[Route('/new', name: 'new', methods: ['POST', 'GET'])]
    public function newBudget(Request $request):Response
    {
        $breadcrumb = new Breadcrumb([
            new BreadcrumbItem('Mes budgets', $this->generateUrl('app_budget_index')),
            new BreadcrumbItem('Ajouter une categorie')
        ]);

        $budget = new Budget;
        $form = $this->createForm(BudgetType::class, $budget);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->save($budget);

            return $this->redirectToRoute('app_budget_edit', [
                'id' => $budget->getId(),
            ]);
        }

        return $this->renderForm('budget/new.html.twig', compact('form', 'budget', 'breadcrumb'));
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request):Response
    {
        return $this->render('budget/index.html.twig', $this->service->index($request));
    }

}