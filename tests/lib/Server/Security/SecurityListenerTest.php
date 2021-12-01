<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Rest\Server\Security;

use Ibexa\Contracts\Core\Repository\PermissionResolver;
use Ibexa\Contracts\Core\Repository\Values\User\User;
use Ibexa\Core\MVC\Symfony\Security\UserInterface;
use Ibexa\Rest\Server\Security\EventListener\SecurityListener;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityListenerTest extends TestCase
{
    /** @var \Ibexa\Contracts\Core\Repository\PermissionResolver|\PHPUnit\Framework\MockObject\MockObject */
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

class_alias(SecurityListenerTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Security\SecurityListenerTest');
