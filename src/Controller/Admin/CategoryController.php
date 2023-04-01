<?php 
namespace App\Controller\Admin;

use App\Entity\Category;
use App\Service\CategoryService;
use App\Form\Admin\EditCategoryType;
use App\Form\Admin\CreateCategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        $category = new Category;
        $form = $this->createForm(CreateCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->save($category);

            $this->addFlash('success', 'Categorie enregistré.');
            return $this->redirectToRoute('admin_category_index');
        }

        return $this->renderForm('admin/category/new.html.twig', compact('form', 'category'));
    }
    
    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function editCategory(Category $category, Request $request):Response 
    {
        $form = $this->createForm(EditCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->service->save($category);

            $this->addFlash('success', 'Categorie enregistré.');
            return $this->redirectToRoute('admin_category_edit', [
                'id' => $category->getId(),
            ]);
        }

        return $this->renderForm('admin/category/edit.html.twig', compact('form', 'category'));
    }

}
