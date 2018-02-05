<?php


namespace App\Security;

use App\Entity\ApplicationUser;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GoogleAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $em;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManager $em, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
    }

    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() === '/connect/check') {

            $token = $this->fetchAccessToken($this->getOauthClient());

            $oauthUser = $this->getOauthClient()->fetchUserFromToken($token);

            $user =
                $this->em->getRepository('App:ApplicationUser')
                    ->findOneBy(['email' => $oauthUser->getEmail()]);

            if (null === $user) {
                $user = new ApplicationUser();
                $user->setEmail($oauthUser->getEmail());
            }

            $user->setToken($token->getToken());
            $user->setExpiresAt($token->getExpires());

            $this->em->persist($user);
            $this->em->flush($user);

            return false;
        }

        if ($request->headers->has('Authorization')) {

            $authHeaders = explode(' ', $request->headers->get('Authorization'));

            // @todo: check there are 2 elements
            //

            $token = $authHeaders[1];

            return $token;
        }

        throw new AuthenticationException('Auth required');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $credentials;

        // 1) have they logged in with Facebook before? Easy!
        $existingUser =
            $this->em->getRepository('App:ApplicationUser')
            ->findOneBy(['token' => $token]);

        return $existingUser;

    }



    /**
     * Returns a response that directs the user to authenticate.
     *
     * This is called when an anonymous request accesses a resource that
     * requires authentication. The job of this method is to return some
     * response that "helps" the user start into the authentication process.
     *
     * Examples:
     *  A) For a form login, you might redirect to the login page
     *      return new RedirectResponse('/login');
     *  B) For an API token authentication system, you return a 401 response
     *      return new Response('Auth header required', 401);
     *
     * @param Request $request The request that resulted in an AuthenticationException
     * @param AuthenticationException $authException The exception that started the authentication process
     *
     * @return Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => $authException ? $authException->getMessage() : 'Authentication header required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Called when authentication executed, but failed (e.g. wrong username password).
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the login page or a 403 response.
     *
     * If you return null, the request will continue, but the user will
     * not be authenticated. This is probably not what you want to do.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
    }

    /**
     * Called when authentication executed and was successful!
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the last page they visited.
     *
     * If you return null, the current request will continue, and the user
     * will be authenticated. This makes sense, for example, with an API.
     *
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey The provider (i.e. firewall) key
     *
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // TODO: Implement onAuthenticationSuccess() method.
    }

    private function getOauthClient(): \KnpU\OAuth2ClientBundle\Client\OAuth2Client
    {
        return $this->clientRegistry->getClient('google');
    }
}