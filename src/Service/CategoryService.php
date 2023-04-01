<?php 
namespace App\Service;

use App\Entity\Category;
use Cocur\Slugify\Slugify;
use App\Utils\ServiceTrait;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CategoryService 
{
    use ServiceTrait;

    private $slugify;

    public function __construct(
        private EntityManagerInterface $manager,
        private ValidatorInterface $validator,
        private PaginatorInterface $paginator,
        private CategoryRepository $repository, 
    ) {
        $this->slugify = new Slugify;
    }
        
    /**
     * index
     *
     * @param  mixed $request
     * @return array
     */
    public function index(Request $request):array 
    {
        $data = $this->repository->findAll();

        $paginatedCategories = $this->paginator->paginate(
            $data, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->getInt('nbItems', 10) /*limit per page*/
        );

        return compact('paginatedCategories');
    }
    
    /**
     * save
     *
     * @param  mixed $category
     * @return void
     */
    public function save(Category $category):void
    {
        $category->getId() !== null ? $category->setUpdatedAt($this->now()) : $category->setCreatedAt($this->now());
        $category->setSlug($this->slugify->slugify($category->getSlug() ?? $category->getName()));

        $this->manager->persist($category);
        $this->manager->flush();
    }
}
