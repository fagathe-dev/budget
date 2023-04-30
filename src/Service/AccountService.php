<?php 
namespace App\Service;

use App\Entity\User;
use App\Utils\ServiceTrait;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AccountService
{

    use ServiceTrait;

    /**
     * @var Session $session
     */
    private $session;

    public function __construct(
        private UserRepository $repository,
        private ValidatorInterface $validator,
        private SerializerInterface $serializer,
        private UserService $userService
    ) {
        $this->session = new Session;
    }

    public function save(User $user):void
    {
        $this->userService->save($user);
    } 

}