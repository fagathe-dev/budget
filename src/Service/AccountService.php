<?php 
namespace App\Service;

use Exception;
use App\Entity\User;
use App\Entity\UserToken;
use App\Utils\ServiceTrait;
use App\Repository\UserRepository;
use App\Repository\UserTokenRepository;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        private UserPasswordHasherInterface $hasher,
        private UserTokenRepository $userTokenRepository,
        private UrlGeneratorInterface $router
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
    
    /**
     * emailVerify
     *
     * @param  mixed $email
     * @return void
     */
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
    
    /**
     * verifyEmail
     *
     * @param  mixed $token
     * @return void
     */
    public function verifyEmail(?string $token):void
    {
        $userToken = $this->userTokenRepository->findOneBy(['token' => $token]);
        $link =  '<br/> <span>Rendez-vous sur <a href=\"' . $this->router->generate('app_account_index') . '\">votre  compte</a> pour générer un nouveau lien</span>';
        $err_msg = 'Ce lien est invalide ! '. $link;
        
        if ($userToken instanceof UserToken) {
            $user = $userToken->getUser();
            $email = $userToken->getData()['email'] ?? '';

            if ($this->userService->checkUserToken($userToken) === false) {
                $this->session->getFlashBag()->add('danger', 'Ce lien est expiré !' . $link);
                return;
            }

            if ($email === '' || $user === null) {
                $this->session->getFlashBag()->add('danger', $err_msg);
                return;
            }

            $user->setEmail($email)
                ->setUpdatedAt($this->now())
                ->removeToken($userToken)
            ;
            
            $this->repository->save($user, true);

            $this->session->getFlashBag()->add('success', 'Votre changement d\'adresse e-mail a bien été pris en compte ✅');
            return;
        }

        $this->session->getFlashBag()->add('danger', $err_msg);
    }
}