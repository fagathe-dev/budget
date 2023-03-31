<?php 
namespace App\Service;

use App\Entity\User;
use DateTimeImmutable;
use Cocur\Slugify\Slugify;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserService 
{

    private $slugify;

    public function __construct(
        private EntityManagerInterface $manager,
        private ValidatorInterface $validator,
        private PaginatorInterface $paginator,
        private UserRepository $repository, 
        private UserPasswordHasherInterface $hasher
    ) {
        $this->slugify = new Slugify;
    }
    
    /**
     * save
     *
     * @param  mixed $user
     * @return void
     */
    public function save(User $user):void 
    {
        $user->getId() !== null ? $user->setUpdatedAt(new DateTimeImmutable) : $user->setRegisteredAt(new DateTimeImmutable);
        $user->setImage(null)
            ->setPassword($this->hasher->hashPassword($user, $user->getPassword()))
            ->setIsConfirm(false);

        $this->manager->persist($user);
        $this->manager->flush();
    }
    
    /**
     * index
     *
     * @param  mixed $request
     * @return array
     */
    public function index(Request $request):array 
    {
        $data = $this->repository->findUsersAdmin();

        $paginatedUsers = $this->paginator->paginate(
            $data, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $request->query->getInt('nbItems', 10) /*limit per page*/
        );

        return compact('paginatedUsers');
    }

}