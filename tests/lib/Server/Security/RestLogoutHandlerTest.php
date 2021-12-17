<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Security;

use Ibexa\Core\MVC\ConfigResolverInterface;
use Ibexa\Rest\Server\Security\RestLogoutHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class RestLogoutHandlerTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $configResolver;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $session;

    protected function setUp(): void
    {
        parent::setUp();
        $this->configResolver = $this->createMock(ConfigResolverInterface::class);
        $this->session = $this->createMock(SessionInterface::class);
    }

    public function testLogoutWithoutSiteaccessSessionSettings()
    {
        $sessionId = 'eZSESSID';
        $this->session
            ->expects($this->once())
            ->method('getName')
            ->willReturn($sessionId);
        $request = new Request();
        $request->setSession($this->session);
        $request->attributes->set('is_rest_request', true);
        $this->configResolver
            ->expects($this->once())
            ->method('getParameter')
            ->with('session')
            ->willReturn([]);
        $response = new Response();
        $response->headers = $this->createMock(ResponseHeaderBag::class);
        $response->headers
            ->expects($this->once())
            ->method('clearCookie')
            ->with($sessionId);
        $logoutHandler = new RestLogoutHandler($this->configResolver);
        $logoutHandler->logout(
            $request,
            $response,
            $this->createMock(TokenInterface::class)
        );
    }

    public function testLogoutWithSiteaccessSessionSettings()
    {
        $sessionId = 'eZSESSID';
        $this->session
            ->expects($this->once())
            ->method('getName')
            ->willReturn($sessionId);
        $request = new Request();
        $request->setSession($this->session);
        $request->attributes->set('is_rest_request', true);
        $sessionSettings = [
            'cookie_path' => '/',
            'cookie_domain' => 'ez.no',
        ];
        $this->configResolver
            ->expects($this->once())
            ->method('getParameter')
            ->with('session')
            ->willReturn($sessionSettings);
        $response = new Response();
        $response->headers = $this->createMock(ResponseHeaderBag::class);
        $response->headers
            ->expects($this->once())
            ->method('clearCookie')
            ->with($sessionId, $sessionSettings['cookie_path'], $sessionSettings['cookie_domain']);
        $logoutHandler = new RestLogoutHandler($this->configResolver);
        $logoutHandler->logout(
            $request,
            $response,
            $this->createMock(TokenInterface::class)
        );
    }

    public function testLogoutNotRest()
    {
        $session = $this->createMock(SessionInterface::class);
        $session
            ->expects($this->never())
            ->method('getName');

        $request = new Request();
        $request->setSession($session);

        $response = new Response();
        $response->headers = $this->createMock(ResponseHeaderBag::class);
        $response->headers
            ->expects($this->never())
            ->method('clearCookie');

        $logoutHandler = new RestLogoutHandler($this->configResolver);
        $logoutHandler->logout(
            $request,
            $response,
            $this->createMock(TokenInterface::class)
        );
    }
}

class_alias(RestLogoutHandlerTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Security\RestLogoutHandlerTest');
