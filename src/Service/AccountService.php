<?php 
namespace App\Service;

use Exception;
use App\Entity\User;
use App\Utils\ServiceTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
        private UserService $userService,
        private Security $security,
        private UserPasswordHasherInterface $hasher
    ) {
        $this->session = new Session;
    }
    
    /**
     * save
     *
     * @param  mixed $user
     * @return void
     */
    public function save(User $user):void
    {
        $this->userService->save($user);
    } 

    public function updatePassword(?string $password)
    {
        $user = $this->security->getUser();

        $user->setPassword($this->hasher->hashPassword($user, $password))
            ->setUpdatedAt($this->now())
        ;

        $this->repository->save($user, true);
        try {
            $this->repository->save($user, true);
            // TODO: Envoi de mail confirm mot de passe mis à jour
            $this->session->getFlashBag()->add('info', 'Mot de passe mis à jour 🚀');
        } catch (ORMException $e) {
            $this->session->getFlashBag()->add('danger', $e->getMessage());
        } catch (Exception $e) {
            $this->session->getFlashBag()->add('danger', $e->getMessage());
        }
    }

}