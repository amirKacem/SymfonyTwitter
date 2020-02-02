<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private $router;
    private $userRepositry;
    private $csrf;
    private $encoder;

    public function __construct(RouterInterface  $router,UserRepository $userRepository,CsrfTokenManagerInterface   $csrftoken,UserPasswordEncoderInterface $encoder)
    {
        $this->router = $router;
        $this->userRepositry = $userRepository;
        $this->csrf = $csrftoken;
        $this->encoder = $encoder;

    }

    public function supports(Request $request)
    {
        // todo
       return $request->attributes->get('_route')=='login' && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = ['username'=>$request->request->get('username'),
                'password'=>$request->request->get('password'),
                '_csrf_token'=>$request->request->get('_csrf_token')
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );
        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // todo
        $token = new CsrfToken('authenticate',$credentials['_csrf_token']);
        if(!$this->csrf->isTokenValid($token)){
            throw new InvalidCsrfTokenException();
        }

        return $this->userRepositry->findOneBy(['username'=>$credentials['username']]);

    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // todo

        return $this->encoder->isPasswordValid($user,$credentials['password']);
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
