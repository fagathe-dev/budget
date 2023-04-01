<?php 
namespace App\Service;

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
}
