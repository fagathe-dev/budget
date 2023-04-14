<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\Budget;
use App\Utils\ServiceTrait;
use App\Breadcrumb\Breadcrumb;
use App\Breadcrumb\BreadcrumbItem;
use App\Repository\BudgetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class BudgetService
{

    use ServiceTrait;

    /** 
     * @var Session $session
    */
    private $session;

    public function __construct(
        private EntityManagerInterface $manager,
        private BudgetRepository $repository, 
        private Security $security,
        private PaginatorInterface $paginator,
        private UrlGeneratorInterface $router
    ) {
        $this->session = new Session;
    }
    
    /**
     * save
     *
     * @param  mixed $budget
     * @return void
     */
    public function save(Budget $budget):void
    {
        if (!$budget->getUser() instanceof User) {
            $budget->setUser($this->security->getUser());
        }

        $this->repository->save($budget, true);
        $this->session->getFlashBag()->add('info', 'Budget enregistré');
    }
    
    /**
     * index
     *
     * @param  mixed $request
     * @return array
     */
    public function index(Request $request):array 
    {
        $breadcrumb = new Breadcrumb([
            new BreadcrumbItem('Mes budgets', $this->router->generate('app_budget_index'))
        ]);

        $user = $this->security->getUser();

        $paginatedBudgets = $this->paginator->paginate(
            $user->getBudgets(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->getInt('nbItems', 10) /*limit per page*/
        );

        return compact('paginatedBudgets', 'breadcrumb');
    }

    public function delete(Budget $budget):object
    {
        $this->repository->remove($budget, true);
        $this->session->getFlashBag()->add('warning', 'Budget supprimé');

        return $this->sendNoContent();
    }

}