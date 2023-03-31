<?php 
namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserService 
{

    private $slugify;

    public function __construct(
        private EntityManagerInterface $manager,
        private ValidatorInterface $validator,
        private PaginatorInterface $paginator,
        private UserRepository $repository 
    ) {
        $this->slugify = new Slugify;
    }

    public function save(User $user):void 
    {

    }

    public function index():array 
    {
        return [];
    }

}