<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Controller;

use eZ\Publish\API\Repository\PermissionResolver;
use eZ\Publish\API\Repository\UserService;
use eZ\Publish\Core\Base\Exceptions\UnauthorizedException;
use eZ\Publish\Core\MVC\Symfony\Security\Authentication\AuthenticatorInterface;
use EzSystems\EzPlatformRest\Exceptions\NotFoundException;
use EzSystems\EzPlatformRest\Message;
use EzSystems\EzPlatformRest\Server\Controller;
use EzSystems\EzPlatformRest\Server\Values;
use EzSystems\EzPlatformRest\Server\Exceptions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use EzSystems\EzPlatformRest\Server\Security\CsrfTokenManager;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class SessionController extends Controller
{
    /** @var \eZ\Publish\Core\MVC\Symfony\Security\Authentication\AuthenticatorInterface|null */
    private $authenticator;

    /** @var \EzSystems\EzPlatformRest\Server\Security\CsrfTokenManager */
    private $csrfTokenManager;

    /** @var string */
    private $csrfTokenIntention;

    /** @var \eZ\Publish\API\Repository\PermissionResolver */
    private $permissionResolver;

    /** @var \eZ\Publish\API\Repository\UserService */
    private $userService;

    /** @var \Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface */
    private $csrfTokenStorage;

    public function __construct(
        $tokenIntention,
        PermissionResolver $permissionResolver,
        UserService $userService,
        ?AuthenticatorInterface $authenticator = null,
        CsrfTokenManager $csrfTokenManager = null,
        TokenStorageInterface $csrfTokenStorage = null
    ) {
        $this->authenticator = $authenticator;
        $this->csrfTokenIntention = $tokenIntention;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->permissionResolver = $permissionResolver;
        $this->userService = $userService;
        $this->csrfTokenStorage = $csrfTokenStorage;
    }

    /**
     * Creates a new session based on the credentials provided as POST parameters.
     *
     * @throws \eZ\Publish\Core\Base\Exceptions\UnauthorizedException If the login or password are incorrect or invalid CSRF
     *
     * @return Values\UserSession|Values\Conflict
     */
    public function createSessionAction(Request $request)
    {
        /** @var $sessionInput \EzSystems\EzPlatformRest\Server\Values\SessionInput */
        $sessionInput = $this->inputDispatcher->parse(
            new Message(
                ['Content-Type' => $request->headers->get('Content-Type')],
                $request->getContent()
            )
        );
        $request->attributes->set('username', $sessionInput->login);
        $request->attributes->set('password', $sessionInput->password);

        try {
            $session = $request->getSession();
            if ($session->isStarted() && $this->hasStoredCsrfToken()) {
                $this->checkCsrfToken($request);
            }

            $token = $this->getAuthenticator()->authenticate($request);
            $csrfToken = $this->getCsrfToken();

            return new Values\UserSession(
                $token->getUser()->getAPIUser(),
                $session->getName(),
                $session->getId(),
                $csrfToken,
                !$token->hasAttribute('isFromSession')
            );
        } catch (Exceptions\UserConflictException $e) {
            // Already logged in with another user, this will be converted to HTTP status 409
            return new Values\Conflict();
        } catch (AuthenticationException $e) {
            $this->getAuthenticator()->logout($request);
            throw new UnauthorizedException('Invalid login or password', $request->getPathInfo());
        } catch (AccessDeniedException $e) {
            $this->getAuthenticator()->logout($request);
            throw new UnauthorizedException($e->getMessage(), $request->getPathInfo());
        }
    }

    /**
     * Refresh given session.
     *
     * @param string $sessionId
     *
     * @throws \EzSystems\EzPlatformRest\Exceptions\NotFoundException
     *
     * @return \EzSystems\EzPlatformRest\Server\Values\UserSession
     */
    public function refreshSessionAction($sessionId, Request $request)
    {
        $session = $request->getSession();

        if ($session === null || !$session->isStarted() || $session->getId() != $sessionId || !$this->hasStoredCsrfToken()) {
            $response = $this->getAuthenticator()->logout($request);
            $response->setStatusCode(404);

            return $response;
        }

        $this->checkCsrfToken($request);
        $currentUser = $this->userService->loadUser(
            $this->permissionResolver->getCurrentUserReference()->getUserId()
        );

        return new Values\UserSession(
            $currentUser,
            $session->getName(),
            $session->getId(),
            $request->headers->get('X-CSRF-Token'),
            false
        );
    }

    /**
     * Deletes given session.
     *
     * @param string $sessionId
     *
     * @return Values\DeletedUserSession
     *
     * @throws NotFoundException
     */
    public function deleteSessionAction($sessionId, Request $request)
    {
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();
        if (!$session->isStarted() || $session->getId() != $sessionId || !$this->hasStoredCsrfToken()) {
            $response = $this->getAuthenticator()->logout($request);
            $response->setStatusCode(404);

            return $response;
        }

        $this->checkCsrfToken($request);

        return new Values\DeletedUserSession($this->getAuthenticator()->logout($request));
    }

    /**
     * Tests if a CSRF token is stored.
     *
     * @return bool
     */
    private function hasStoredCsrfToken()
    {
        if ($this->csrfTokenManager === null) {
            return true;
        }

        return $this->csrfTokenManager->hasToken($this->csrfTokenIntention);
    }

    /**
     * Checks the presence / validity of the CSRF token.
     *
     * @param Request $request
     *
     * @throws UnauthorizedException if the token is missing or invalid
     */
    private function checkCsrfToken(Request $request)
    {
        if ($this->csrfTokenManager === null) {
            return;
        }

        $exception = new UnauthorizedException(
            'Missing or invalid CSRF token',
            $request->getMethod() . ' ' . $request->getPathInfo()
        );

        if (!$request->headers->has('X-CSRF-Token')) {
            throw $exception;
        }

        $csrfToken = new CsrfToken(
            $this->csrfTokenIntention,
            $request->headers->get('X-CSRF-Token')
        );

        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            throw $exception;
        }
    }

    /**
     * Returns the csrf token for REST. The token is generated if it doesn't exist.
     *
     * @return string the csrf token, or an empty string if csrf check is disabled
     */
    private function getCsrfToken()
    {
        if ($this->csrfTokenManager === null) {
            return '';
        }

        return $this->csrfTokenManager->getToken($this->csrfTokenIntention)->getValue();
    }

    private function getAuthenticator(): ?AuthenticatorInterface
    {
        if (null === $this->authenticator) {
            throw new \RuntimeException(
                sprintf(
                    "No %s instance injected. Ensure 'ezpublish_rest_session' is configured under your firewall",
                    AuthenticatorInterface::class
                )
            );
        }

        return $this->authenticator;
    }
}
