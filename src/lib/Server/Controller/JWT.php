<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Controller;

use eZ\Publish\Core\Base\Exceptions\UnauthorizedException;
use eZ\Publish\Core\MVC\Symfony\Security\Authentication\AuthenticatorInterface;
use EzSystems\EzPlatformRest\Message;
use EzSystems\EzPlatformRest\Server\Controller as RestController;
use EzSystems\EzPlatformRest\Server\Security\JWTUser;
use EzSystems\EzPlatformRest\Server\Values;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * @internal
 */
final class JWT extends RestController
{
    /** @var \Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface */
    private $tokenManager;

    /** @var \eZ\Publish\Core\MVC\Symfony\Security\Authentication\AuthenticatorInterface|null */
    private $authenticator;

    public function __construct(
        JWTTokenManagerInterface $tokenManager,
        ?AuthenticatorInterface $authenticator = null
    ) {
        $this->tokenManager = $tokenManager;
        $this->authenticator = $authenticator;
    }

    public function createToken(Request $request): Values\JWT
    {
        /** @var \EzSystems\EzPlatformRest\Server\Values\JWTInput $jwtTokenInput */
        $jwtTokenInput = $this->inputDispatcher->parse(
            new Message(
                ['Content-Type' => $request->headers->get('Content-Type')],
                $request->getContent()
            )
        );

        try {
            $request->attributes->set('username', $jwtTokenInput->username);
            $request->attributes->set('password', (string) $jwtTokenInput->password);

            $user = $this->getAuthenticator()->authenticate($request)->getUser();

            $jwtToken = $this->tokenManager->create(
                new JWTUser($user, $jwtTokenInput->username)
            );

            return new Values\JWT($jwtToken);
        } catch (AuthenticationException $e) {
            $this->getAuthenticator()->logout($request);
            throw new UnauthorizedException('Invalid login or password', $request->getPathInfo());
        }
    }

    private function getAuthenticator(): AuthenticatorInterface
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
