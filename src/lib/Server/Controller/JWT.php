<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Controller;

use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\UserService;
use eZ\Publish\Core\Base\Exceptions\UnauthorizedException;
use eZ\Publish\Core\MVC\Symfony\Security\User;
use EzSystems\EzPlatformRest\Message;
use EzSystems\EzPlatformRest\Server\Controller as RestController;
use EzSystems\EzPlatformRest\Server\Values;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class JWT extends RestController
{
    /** @var \eZ\Publish\API\Repository\UserService */
    private $userService;

    /** @var \Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface */
    private $tokenManager;

    public function __construct(
        UserService $userService,
        JWTTokenManagerInterface $tokenManager
    ) {
        $this->userService = $userService;
        $this->tokenManager = $tokenManager;
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
            $user = $this->userService->loadUserByLogin($jwtTokenInput->username);
            if (!$this->userService->checkUserCredentials($user, $jwtTokenInput->password)) {
                throw new BadCredentialsException();
            }
            $token = $this->tokenManager->create(new User($user, ['ROLE_USER']));

            return new Values\JWT($token);
        } catch (NotFoundException | BadCredentialsException $e) {
            throw new UnauthorizedException('Invalid username or password', $request->getPathInfo());
        }
    }
}
