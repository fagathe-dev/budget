<?php 
namespace App\Service;

use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserService 
{

    private $slugify;

    public function __construct(
        private EntityManagerInterface $manager,
        private ValidatorInterface $validator
    ) {
        $this->slugify = new Slugify;
    }

    public function save(User $user):void 
    {

    }

}