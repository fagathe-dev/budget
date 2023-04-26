<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\UserToken;
use DateTimeImmutable;
use Cocur\Slugify\Slugify;
use App\Utils\ServiceTrait;
use App\Breadcrumb\Breadcrumb;
use App\Breadcrumb\BreadcrumbItem;
use App\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
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
        private UrlGeneratorInterface $router
    ) {
        $this->slugify = new Slugify;
        $this->session = new Session;
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

        try {
            $this->repository->save($user, true);
            // TODO: Envoi de mail confirm création de compte
        } catch (ORMException $e) {
            $this->session->getFlashBag()->add('danger', $e->getMessage());
        } catch (Exception $e) {
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

    public function delete(User $user): object
    {
        $this->repository->remove($user, true);

        return $this->sendNoContent();
    }

    public function sendCreateForgotPasswordMail(?string $userEmail = null):bool 
    {
        $user = $this->repository->findOneBy(['email' => $userEmail]);

        if ($user instanceof User) {
            $token = new UserToken;
            $token->setAction(UserToken::RESET_PASSWORD_TOKEN)
                ->setToken($this->generateToken())
                ->setCreatedAt(new DateTimeImmutable)
                ->setExpiredAt((new DateTimeImmutable)->modify('+2 days'))
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

}