<?php
namespace App\Service;

use App\Breadcrumb\Breadcrumb;
use App\Breadcrumb\BreadcrumbGenerator;
use App\Breadcrumb\BreadcrumbItem;
use App\Entity\User;
use DateTimeImmutable;
use Cocur\Slugify\Slugify;
use App\Utils\ServiceTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class UserService
{

    use ServiceTrait;

    private $slugify;

    public function __construct(
        private EntityManagerInterface $manager,
        private ValidatorInterface $validator,
        private PaginatorInterface $paginator,
        private UserRepository $repository,
        private UserPasswordHasherInterface $hasher,
        private UrlGeneratorInterface $router
    ) {
        $this->slugify = new Slugify;
    }

    /**
     * save
     *
     * @param  mixed $user
     * @return void
     */
    public function save(User $user): void
    {
        $user->getId() !== null ? $user->setUpdatedAt(new DateTimeImmutable) : $user->setRegisteredAt(new DateTimeImmutable);
        $user->setPassword($this->hasher->hashPassword($user, $user->getPassword()))
            ->setConfirm($user->getConfirm() ?? false);

        $this->repository->save($user, true);
    }

    /**
     * index
     *
     * @param  mixed $request
     * @return array
     */
    public function index(Request $request): array
    {
        $breadcrumb = new Breadcrumb([
            new BreadcrumbItem('Liste des utilisateurs', $this->router->generate('admin_user_index'))
        ]);

        $data = $this->repository->findUsersAdmin();

        $paginatedUsers = $this->paginator->paginate(
            $data, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->getInt('nbItems', 10) /*limit per page*/
        );

        return compact('paginatedUsers', 'breadcrumb');
    }

    public function delete(User $user): object
    {
        $this->manager->remove($user);
        $this->manager->flush();

        return $this->sendNoContent();
    }
}