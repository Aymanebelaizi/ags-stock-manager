<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator) {}

   public function authenticate(Request $request): Passport
{
    $email = $request->request->get('email', '');
    $password = $request->request->get('password', '');

    $request->getSession()->set(
        SecurityRequestAttributes::LAST_USERNAME,
        $email
    );

    return new Passport(
        new UserBadge($email),
        new PasswordCredentials($password),
        [
            new CsrfTokenBadge(
                'authenticate',
                $request->request->get('_csrf_token')
            )
        ]
    );
}


  public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response 
{
    $user = $token->getUser();
    
    // On force la redirection vers le Dashboard pour l'Admin
    if (in_array('ROLE_ADMIN', $user->getRoles())) {
        return new RedirectResponse($this->urlGenerator->generate('admin_dashboard'));
    }

    // Sinon vers la liste des produits
    return new RedirectResponse($this->urlGenerator->generate('admin_product_index'));
}

    protected function getLoginUrl(Request $request): string {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}