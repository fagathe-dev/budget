<?php
namespace App\Service;

use Exception;
use App\Entity\User;
use App\Mailer\Email;
use App\Entity\UserToken;
use App\Mailer\MailerEnum;
use Cocur\Slugify\Slugify;
use App\Utils\ServiceTrait;
use App\Breadcrumb\Breadcrumb;
use App\Breadcrumb\BreadcrumbItem;
use App\Repository\UserRepository;
use App\Mailer\Auth\AccountVerifyEmail;
use App\Repository\UserTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserService
{

    use ServiceTrait;

    private $slugify;
    private $session;

    public function __construct(
        private ValidatorInterface $validator,
        private PaginatorInterface $paginator,
        private UserRepository $repository,
        private UserPasswordHasherInterface $hasher,
        private UrlGeneratorInterface $router,
        private UserTokenRepository $userTokenRepository,
        private EntityManagerInterface $manager,
        private AccountVerifyEmail $accountVerifyEmail
    ) {
        $this->slugify = new Slugify;
        $this->session = new Session;
    }

    public function create(User $user, bool $isRegistration = false):void 
    {
        $user->setRegisteredAt($this->now())
            ->setPassword($this->hasher->hashPassword($user, $user->getPassword()))
            ->setConfirm($user->getConfirm() ?? false)
        ;
        $token = (new UserToken)
            ->setToken($this->generateToken())
            ->setExpiredAt($this->now()->modify('+2 days'))
            ->setCreatedAt($this->now())
            ->setAction(UserToken::USER_ACCOUNT_VERIFICATION)
        ;
        $user->addToken($token);
        $email = MailerEnum::getEmail(MailerEnum::ACTION_SEND_TOKEN_ACCOUNT_VERIFY);

        try {
            $this->accountVerifyEmail->emit($email->setData(compact('token')));
            $this->save($user);
        } catch (TransportException $e) {
            $this->session->getFlashBag()->add('danger', $e->getMessage());
        }

    }

    public function update(User $user):void 
    {
        $user->setUpdatedAt($this->now());

        $this->save($user);
    }

    /**
     * save
     *
     * @param  mixed $user
     * @return void
     */
    public function save(User $user): void
    {
        try {
            $this->repository->save($user, true);

            $this->session->getFlashBag()->add('info', 'Utilisateur enregistré.');

        } catch (ORMException $e) {
            $this->session->getFlashBag()->add('danger', $e->getMessage());
        }
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
    
    /**
     * delete
     *
     * @param  mixed $user
     * @return object
     */
    public function delete(User $user): object
    {
        $this->repository->remove($user, true);

        return $this->sendNoContent();
    }
    
    /**
     * sendCreateForgotPasswordMail
     *
     * @param  mixed $userEmail
     * @return bool
     */
    public function sendCreateForgotPasswordMail(?string $userEmail = null):bool 
    {
        $user = $this->repository->findOneBy(['email' => $userEmail]);

        if ($user instanceof User) {
            $token = new UserToken;
            $token->setAction(UserToken::RESET_PASSWORD_TOKEN)
                ->setToken($this->generateToken())
                ->setCreatedAt($this->now())
                ->setExpiredAt($this->now()->modify('+2 days'))
            ;

            $user->addToken($token);
            try {
                $this->repository->save($user, true);
                // TODO: Envoi de mail avec token reset password
            } catch (Exception $e) {
                $this->session->getFlashBag()->add('danger', $e->getMessage());
                return false;
            }
        }

        $this->session->getFlashBag()->add('success', 'Votre demande a été pris en compte 👍');
        return true;
    }
    
    /**
     * resetPassword
     *
     * @param  mixed $token
     * @param  mixed $password
     * @return bool
     */
    public function resetPassword(UserToken $token, ?string $password):bool 
    {   
        $user = $token->getUser();
        $user->setPassword(
            $this->hasher->hashPassword($user, $password)
        );
        
        try {
            $user->removeToken($token);
            $this->repository->save($user, true);
            // TODO: Envoi de mail confirmation modification de mot de passe
        } catch (Exception $e) {
            $this->session->getFlashBag()->add('danger', $e->getMessage());
            return false;
        }

        $this->session->getFlashBag()->add('success', 'Votre mot de passe a bien été modifié 🚀');
        return true;
    }
    
    /**
     * getUserToken
     *
     * @param  mixed $token
     * @return UserToken
     */
    public function getUserToken(?string $token):?UserToken 
    {
        return $this->userTokenRepository->findOneBy(['token' => $token]);
    }
    
    /**
     * getUserToken
     *
     * @param  mixed $token
     * @return UserToken
     */
    public function checkUserToken(UserToken $token):bool 
    {
        return $token->getExpiredAt() !== null && $this->isDatePast($token->getExpiredAt());
    }
}