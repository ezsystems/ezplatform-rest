<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Bundle\Rest\EventListener;

use Ibexa\Bundle\Rest\EventListener\CsrfListener;
use Ibexa\Core\Base\Exceptions\UnauthorizedException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CsrfListenerTest extends EventListenerTest
{
    public const VALID_TOKEN = 'valid';
    public const INVALID_TOKEN = 'invalid';
    public const INTENTION = 'rest';

    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface */
    protected $eventDispatcherMock;

    /**
     * If set to null before initializing mocks, Request::getSession() is expected not to be called.
     *
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $sessionMock;

    protected $sessionIsStarted = true;

    protected $csrfTokenHeaderValue = self::VALID_TOKEN;

    /**
     * Route returned by Request::get( '_route' )
     * If set to false, get( '_route' ) is expected not to be called.
     *
     * @var string
     */
    protected $route = 'ezpublish_rest_something';

    /**
     * If set to false, Request::getRequestMethod() is expected not to be called.
     */
    protected $requestMethod = 'POST';

    public function provideExpectedSubscribedEventTypes()
    {
        return [
            [[KernelEvents::REQUEST]],
        ];
    }

    public function testIsNotRestRequest()
    {
        $this->isRestRequest = false;

        $this->requestMethod = false;
        $this->sessionMock = false;
        $this->route = false;
        $this->csrfTokenHeaderValue = null;

        $listener = $this->getEventListener();
        $listener->onKernelRequest($this->getEvent());
    }

    public function testCsrfDisabled()
    {
        $this->requestMethod = false;
        $this->sessionMock = false;
        $this->route = false;
        $this->csrfTokenHeaderValue = null;

        $this->getEventListener(false)->onKernelRequest($this->getEvent());
    }

    public function testNoSessionStarted()
    {
        $this->sessionIsStarted = false;

        $this->requestMethod = false;
        $this->route = false;
        $this->csrfTokenHeaderValue = null;

        $this->getEventListener()->onKernelRequest($this->getEvent());
    }

    /**
     * Tests that method CSRF check don't apply to are indeed ignored.
     *
     * @param string $ignoredMethod
     * @dataProvider getIgnoredRequestMethods
     */
    public function testIgnoredRequestMethods($ignoredMethod)
    {
        $this->requestMethod = $ignoredMethod;
        $this->route = false;
        $this->csrfTokenHeaderValue = null;

        $this->getEventListener()->onKernelRequest($this->getEvent());
    }

    public function getIgnoredRequestMethods()
    {
        return [
            ['GET'],
            ['HEAD'],
            ['OPTIONS'],
        ];
    }

    /**
     * @dataProvider provideSessionRoutes
     */
    public function testSessionRequests($route)
    {
        $this->route = $route;
        $this->csrfTokenHeaderValue = null;

        $this->getEventListener()->onKernelRequest($this->getEvent());
    }

    public static function provideSessionRoutes()
    {
        return [
            ['ezpublish_rest_createSession'],
            ['ezpublish_rest_refreshSession'],
            ['ezpublish_rest_deleteSession'],
        ];
    }

    public function testSkipCsrfProtection(): void
    {
        $this->enableCsrfProtection = false;
        $this->csrfTokenHeaderValue = null;

        $listener = $this->getEventListener();
        $listener->onKernelRequest($this->getEvent());
    }

    public function testNoHeader()
    {
        $this->expectException(UnauthorizedException::class);

        $this->csrfTokenHeaderValue = false;

        $this->getEventListener()->onKernelRequest($this->getEvent());
    }

    public function testInvalidToken()
    {
        $this->expectException(UnauthorizedException::class);

        $this->csrfTokenHeaderValue = self::INVALID_TOKEN;

        $this->getEventListener()->onKernelRequest($this->getEvent());
    }

    public function testValidToken()
    {
        $this->getEventDispatcherMock()
            ->expects($this->once())
            ->method('dispatch');

        $this->getEventListener()->onKernelRequest($this->getEvent());
    }

    /**
     * @return \Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getCsrfProviderMock()
    {
        $provider = $this->createMock(CsrfTokenManagerInterface::class);
        $provider->expects($this->any())
            ->method('isTokenValid')
            ->willReturnCallback(
                static function (CsrfToken $token) {
                    if ($token == new CsrfToken(self::INTENTION, self::VALID_TOKEN)) {
                        return true;
                    }

                    return false;
                }
            );

        return $provider;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpKernel\Event\RequestEvent
     */
    protected function getEvent($class = null)
    {
        if (!isset($this->event)) {
            parent::getEvent(RequestEvent::class);

            $this->event
                ->expects($this->any())
                ->method('getRequestType')
                ->willReturn($this->requestType);
        }

        return $this->event;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\SessionInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getSessionMock()
    {
        if (!isset($this->sessionMock)) {
            $this->sessionMock = $this->createMock(SessionInterface::class);
            $this->sessionMock
                ->expects($this->atLeastOnce())
                ->method('isStarted')
                ->willReturn($this->sessionIsStarted);
        }

        return $this->sessionMock;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\ParameterBag|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getRequestHeadersMock()
    {
        if (!isset($this->requestHeadersMock)) {
            $this->requestHeadersMock = parent::getRequestHeadersMock();

            if ($this->csrfTokenHeaderValue === null) {
                $this->requestHeadersMock
                    ->expects($this->never())
                    ->method('has');

                $this->requestHeadersMock
                    ->expects($this->never())
                    ->method('get');
            } else {
                $this->requestHeadersMock
                    ->expects($this->atLeastOnce())
                    ->method('has')
                    ->with(CsrfListener::CSRF_TOKEN_HEADER)
                    ->willReturn(true);

                $this->requestHeadersMock
                    ->expects($this->atLeastOnce())
                    ->method('get')
                    ->with(CsrfListener::CSRF_TOKEN_HEADER)
                    ->willReturn($this->csrfTokenHeaderValue);
            }
        }

        return $this->requestHeadersMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\Request
     */
    protected function getRequestMock()
    {
        if (!isset($this->requestMock)) {
            $this->requestMock = parent::getRequestMock();

            if ($this->sessionMock === false) {
                $this->requestMock
                    ->expects($this->never())
                    ->method('getSession');
            } else {
                $this->requestMock
                    ->expects($this->atLeastOnce())
                    ->method('getSession')
                    ->willReturn($this->getSessionMock());
            }

            if ($this->route === false) {
                $this->requestMock
                    ->expects($this->never())
                    ->method('get');
            } else {
                $this->requestMock
                    ->expects($this->atLeastOnce())
                    ->method('get')
                    ->with('_route')
                    ->willReturn($this->route);
            }
        }

        return $this->requestMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected function getEventDispatcherMock()
    {
        if (!isset($this->eventDispatcherMock)) {
            $this->eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);
        }

        return $this->eventDispatcherMock;
    }

    /**
     * @param bool $csrfEnabled
     *
     * @return \Ibexa\Bundle\Rest\EventListener\CsrfListener
     */
    protected function getEventListener($csrfEnabled = true)
    {
        if ($csrfEnabled) {
            return new CsrfListener(
                $this->getEventDispatcherMock(),
                $csrfEnabled,
                self::INTENTION,
                $this->getCsrfProviderMock()
            );
        }

        return new CsrfListener(
            $this->getEventDispatcherMock(),
            $csrfEnabled,
            self::INTENTION
        );
    }
}

class_alias(CsrfListenerTest::class, 'EzSystems\EzPlatformRestBundle\Tests\EventListener\CsrfListenerTest');
