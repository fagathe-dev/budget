<?php 
namespace App\Service;

use Exception;
use App\Entity\User;
use App\Entity\UserToken;
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

    /**
     * @var User
     */
    private $user;

    public function __construct(
        private UserRepository $repository,
        private ValidatorInterface $validator,
        private SerializerInterface $serializer,
        private UserService $userService,
        private Security $security,
        private UserPasswordHasherInterface $hasher
    ) {
        $this->session = new Session;
        $this->user = $this->security->getUser();
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
    
    /**
     * updatePassword
     *
     * @param  mixed $password
     * @return void
     */
    public function updatePassword(?string $password):void
    {
        $this->user->setPassword($this->hasher->hashPassword($this->user, $password))
            ->setUpdatedAt($this->now())
        ;

        try {
            $this->repository->save($this->user, true);
            // TODO: Envoi de mail confirm mot de passe mis à jour
            $this->session->getFlashBag()->add('info', 'Mot de passe mis à jour 🚀');
        } catch (ORMException $e) {
            $this->session->getFlashBag()->add('danger', $e->getMessage());
        } catch (Exception $e) {
            $this->session->getFlashBag()->add('danger', $e->getMessage());
        }
    }

    public function emailVerify(?string $email):void
    {
        $token = new UserToken;
        $token->setAction(UserToken::USER_EMAIL_VERIFICATION)
            ->setCreatedAt($this->now())
            ->setExpiredAt($this->now()->modify('+24 hours'))
            ->setData(compact('email'))
            ->setToken($this->generateToken())
        ;

        try {
            $this->repository->save($this->user->addToken($token), true);
            // TODO: Envoi de mail verification nouvelle adresse e-mail
            $this->session->getFlashBag()->add('info', 'Votre demande a bien été pris en compte.');
        } catch (ORMException $e) {
            $this->session->getFlashBag()->add('danger', $e->getMessage());
        } catch (Exception $e) {
            $this->session->getFlashBag()->add('danger', $e->getMessage());
        }
        
    }

}