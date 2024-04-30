<?php
declare(strict_types=1);


namespace App\Security;

use App\Service\AuthServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class JwtAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private AuthServiceInterface $authService
    ){}

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $credentials = $request->headers->get('Authorization');
        $token = preg_replace('/Bearer\s/', '', $credentials);
        try {
            $cognitoUserIdentifier = $this->authService->cognitoUserIdentifier($token);
        } catch (\Exception $e) {
            $cognitoUserIdentifier = 'anonymous';
        }
        return new Passport(
            new UserBadge($cognitoUserIdentifier),
            new CustomCredentials(function () use ($cognitoUserIdentifier) {
                return $cognitoUserIdentifier !== 'anonymous';
            }, $credentials)
        );
    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new Response('Authentication Failed: ' . $exception->getMessage(), Response::HTTP_UNAUTHORIZED);
    }

}