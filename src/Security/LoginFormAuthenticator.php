<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private $router;
    private $userRepositry;
    public function __construct(RouterInterface  $router,UserRepository $userRepository)

    {
        $this->router = $router;
        $this->userRepositry = $userRepository;
    }

    public function supports(Request $request)
    {
        // todo
       return $request->attributes->get('_route')=='login' && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {   return ['username'=>$request->request->get('username'),
                'password'=>$request->request->get('password'),
                '_csrf_token'=>$request->request->get('token')
        ];

        // todo
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // todo
        return $this->userRepositry->findOneBy(['username'=>$credentials['username']]);

    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // todo
        return true;
    }



    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // todo
        return new RedirectResponse($this->router->generate('home'));
    }



    public function supportsRememberMe()
    {
        // todo
    }
    protected function getLoginUrl()
    {
        return $this->router->generate('login');
    }
}
