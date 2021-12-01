<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Controller;

use Ibexa\Core\Base\Exceptions\UnauthorizedException;
use Ibexa\Core\MVC\Symfony\Security\Authentication\AuthenticatorInterface;
use Ibexa\Rest\Message;
use Ibexa\Rest\Server\Controller as RestController;
use Ibexa\Rest\Server\Security\JWTUser;
use Ibexa\Rest\Server\Values;
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

    /** @var \Ibexa\Core\MVC\Symfony\Security\Authentication\AuthenticatorInterface|null */
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
        /** @var \Ibexa\Rest\Server\Values\JWTInput $jwtTokenInput */
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

class_alias(JWT::class, 'EzSystems\EzPlatformRest\Server\Controller\JWT');
