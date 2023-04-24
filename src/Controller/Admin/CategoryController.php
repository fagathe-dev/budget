<?php 
namespace App\Controller\Admin;

use App\Entity\Category;
use App\Breadcrumb\Breadcrumb;
use App\Service\CategoryService;
use App\Breadcrumb\BreadcrumbItem;
use App\Form\Admin\EditCategoryType;
use App\Form\Admin\CreateCategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/category', name: 'admin_category_')]
class CategoryController extends AbstractController
{
    
    public function __construct(
        private CategoryService $service
    ){}

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request):Response 
    {
        return $this->render('admin/category/index.html.twig', $this->service->index($request));
    }
    
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function newCategory(Request $request):Response 
    {

        $breadcrumb = new Breadcrumb([
            new BreadcrumbItem('Liste des categories', $this->generateUrl('admin_category_index')),
            new BreadcrumbItem('Ajouter une categorie')
        ]);

        $category = new Category;
        $form = $this->createForm(CreateCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->save($category);

            $this->addFlash('success', 'Categorie enregistré.');
            return $this->redirectToRoute('admin_category_index');
        }

        return $this->renderForm('admin/category/new.html.twig', compact('form', 'category', 'breadcrumb'));
    }
    
    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function editCategory(Category $category, Request $request):Response 
    {

        $breadcrumb = new Breadcrumb([
            new BreadcrumbItem('Liste des categories', $this->generateUrl('admin_category_index')),
            new BreadcrumbItem('Modifier la categorie ' . $category->getName()),
        ]);

        $form = $this->createForm(EditCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->save($category);

            $this->addFlash('success', 'Categorie enregistré.');
            return $this->redirectToRoute('admin_category_edit', [
                'id' => $category->getId(),
            ]);
        }

        return $this->renderForm('admin/category/edit.html.twig', compact('form', 'category', 'breadcrumb'));
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(Category $category):JsonResponse 
    {
        $response = $this->service->delete($category);

        return $this->json(
            $response->data,
            $response->status,
            $response->headers
        );
    }
    
}
