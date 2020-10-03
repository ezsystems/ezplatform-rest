<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Tests\Server\Security;

use eZ\Publish\API\Repository\PermissionResolver;
use eZ\Publish\API\Repository\Values\User\User;
use eZ\Publish\Core\MVC\Symfony\Security\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use EzSystems\EzPlatformRest\Server\Security\EventListener\SecurityListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityListenerTest extends TestCase
{
    /** @var \eZ\Publish\API\Repository\PermissionResolver|\PHPUnit\Framework\MockObject\MockObject */
    private $permissionResolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->permissionResolver = $this->createMock(PermissionResolver::class);
    }

    public function testOnInteractiveLoginWithJWTUserTokenButNotEzPlatformUser(): void
    {
        $requestMock = $this->createMock(Request::class);
        $tokenMock = $this->createMock(JWTUserToken::class);

        $interactiveLoginEvent = new InteractiveLoginEvent($requestMock, $tokenMock);

        $tokenMock
            ->expects(self::once())
            ->method('getUser')
            ->willReturn(null);

        $securityListener = new SecurityListener($this->permissionResolver);
        $securityListener->onInteractiveLogin($interactiveLoginEvent);
    }

    public function testOnInteractiveLoginWithJWTUserTokenAndEzPlatformUser(): void
    {
        $requestMock = $this->createMock(Request::class);
        $tokenMock = $this->createMock(JWTUserToken::class);

        $interactiveLoginEvent = new InteractiveLoginEvent($requestMock, $tokenMock);

        $userMock = $this->createMock(UserInterface::class);
        $apiUserMock = $this->createMock(User::class);

        $tokenMock
            ->expects(self::once())
            ->method('getUser')
            ->willReturn($userMock);

        $userMock
            ->expects(self::once())
            ->method('getApiUser')
            ->willReturn($apiUserMock);

        $this->permissionResolver
            ->expects(self::once())
            ->method('setCurrentUserReference')
            ->with($apiUserMock);

        $securityListener = new SecurityListener($this->permissionResolver);
        $securityListener->onInteractiveLogin($interactiveLoginEvent);
    }
}
