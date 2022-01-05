<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Bundle\Rest\EventListener;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

abstract class EventListenerTest extends TestCase
{
    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface */
    protected $event;

    /** @var \Symfony\Component\HttpFoundation\Request|\PHPUnit\Framework\MockObject\MockObject */
    protected $requestMock;

    /** @var \Symfony\Component\HttpFoundation\ParameterBag|\PHPUnit\Framework\MockObject\MockObject */
    protected $requestAttributesMock;

    /** @var \Symfony\Component\HttpFoundation\ParameterBag|\PHPUnit\Framework\MockObject\MockObject */
    protected $requestHeadersMock;

    protected $isRestRequest = true;

    protected $requestType = HttpKernelInterface::MASTER_REQUEST;

    protected $requestMethod = false;

    protected $enableCsrfProtection = true;

    /**
     * @dataProvider provideExpectedSubscribedEventTypes
     */
    public function testGetSubscribedEvents($expectedEventTypes)
    {
        $eventListener = $this->getEventListener();

        $supportedEvents = $eventListener->getSubscribedEvents();
        $supportedEventTypes = array_keys($supportedEvents);
        sort($supportedEventTypes);
        sort($expectedEventTypes);

        self::assertEquals($expectedEventTypes, $supportedEventTypes);

        // Check that referenced methods exist
        foreach ($supportedEvents as $method) {
            self::assertTrue(
                method_exists($eventListener, is_array($method) ? $method[0] : $method)
            );
        }
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|$class
     */
    protected function getEvent($class)
    {
        if (!isset($this->event)) {
            $this->event = $this->getMockBuilder($class)
                                ->disableOriginalConstructor()
                                ->getMock();

            $this->event
                ->expects($this->any())
                ->method('getRequest')
                ->willReturn($this->getRequestMock());
        }

        return $this->event;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\ParameterBag|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getRequestAttributesMock()
    {
        if (!isset($this->requestAttributesMock)) {
            $this->requestAttributesMock = $this->createMock(ParameterBag::class);
            $this->requestAttributesMock
                ->expects($this->once())
                ->method('get')
                ->with('is_rest_request')
                ->willReturn($this->isRestRequest);

            $this->requestAttributesMock
                ->method('getBoolean')
                ->with('csrf_protection', true)
                ->willReturn($this->enableCsrfProtection);
        }

        return $this->requestAttributesMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpFoundation\Request
     */
    protected function getRequestMock()
    {
        if (!isset($this->requestMock)) {
            $this->requestMock = $this->createMock(Request::class);
            $this->requestMock->attributes = $this->getRequestAttributesMock();
            $this->requestMock->headers = $this->getRequestHeadersMock();

            if ($this->requestMethod === false) {
                $this->requestMock
                    ->expects($this->never())
                    ->method('getMethod');
            } else {
                $this->requestMock
                    ->expects($this->atLeastOnce())
                    ->method('getMethod')
                    ->willReturn($this->requestMethod);
            }
        }

        return $this->requestMock;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\ParameterBag|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getRequestHeadersMock()
    {
        if (!isset($this->requestHeadersMock)) {
            $this->requestHeadersMock = $this->createMock(ParameterBag::class);
        }

        return $this->requestHeadersMock;
    }

    /**
     * @param bool $csrfEnabled
     *
     * @return \Ibexa\Bundle\Rest\EventListener\CsrfListener
     */
    abstract protected function getEventListener();

    /**
     * Returns an array with the events the listener should be subscribed to.
     */
    abstract public function provideExpectedSubscribedEventTypes();
}

class_alias(EventListenerTest::class, 'EzSystems\EzPlatformRestBundle\Tests\EventListener\EventListenerTest');
