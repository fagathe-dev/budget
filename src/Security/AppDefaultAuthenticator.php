<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class AppDefaultAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    public const DEFAULT_REDIRECT_PATH = 'app_default';
    public const ADMIN_REDIRECT_PATH = 'admin_default';

    /**
     * @var ?User $user
     */
    private $user = null;

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private UserRepository $repository,
        private Security $security
    ) {
    }

    /**
     * authenticate
     *
     * @param  mixed $request
     * @return Passport
     */
    /**
     * authenticate
     *
     * @param  mixed $request
     * @return Passport
     */
    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $this->user = $this->repository->findOneBy(['email' => $email]);

        $request->getSession()->set(Security::LAST_USERNAME, $email);
        $badges = [new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token'))];
        if ($request->request->get('remember_me')) {
            array_push($badges, (new RememberMeBadge())->enable());
        }

        if ($this->user instanceof User) {
            return new Passport(
                new UserBadge($email),
                new PasswordCredentials($request->request->get('password', '')),
                $badges
            );
        }

        throw new CustomUserMessageAuthenticationException('Identifiants incorrects');
    }

    /**
     * onAuthenticationSuccess
     *
     * @param  mixed $request
     * @param  mixed $token
     * @param  mixed $firewallName
     * @return Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        return new RedirectResponse(
            $this->urlGenerator->generate($this->security->isGranted("ROLE_ADMIN")
                ? self::ADMIN_REDIRECT_PATH
                : self::DEFAULT_REDIRECT_PATH)
        );
    }

    /**
     * getLoginUrl
     *
     * @param  mixed $request
     * @return string
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}