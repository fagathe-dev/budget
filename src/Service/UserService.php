<?php 
namespace App\Service;

use App\Entity\User;
use DateTimeImmutable;
use Cocur\Slugify\Slugify;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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

    public function save(User $user):void 
    {
        $user->getId() !== null ? $user->setUpdatedAt(new DateTimeImmutable) : $user->setRegisteredAt(new DateTimeImmutable);
        $user->setImage(null)
            ->setPassword($this->hasher->hashPassword($user, $user->getPassword()))
            ->setIsConfirm(false);

        $this->manager->persist($user);
        $this->manager->flush();
    }

    public function index():array 
    {
        return [];
    }

}