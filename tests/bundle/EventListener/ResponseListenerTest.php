<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Bundle\Rest\EventListener;

use Exception;
use Ibexa\Bundle\Rest\EventListener\ResponseListener;
use Ibexa\Rest\Server\View\AcceptHeaderVisitorDispatcher;
use stdClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

class ResponseListenerTest extends EventListenerTest
{
    /** @var \Ibexa\Rest\Server\View\AcceptHeaderVisitorDispatcher|\PHPUnit\Framework\MockObject\MockObject */
    protected $visitorDispatcherMock;

    /** @var \stdClass */
    protected $eventValue;

    /** @var \Exception */
    protected $exceptionEventValue;

    protected $dispatcherMessage;

    protected $controllerResult;

    /** @var \Symfony\Component\HttpFoundation\Response */
    protected $response;

    /** @var \Symfony\Contracts\EventDispatcher\Event */
    protected $event;

    /** @var \Symfony\Component\HttpKernel\KernelInterface|\PHPUnit\Framework\MockObject\MockObject */
    protected $kernelMock;

    public function setUp(): void
    {
        $this->eventValue = new stdClass();
        $this->exceptionEventValue = new Exception();
        $this->response = new Response('BODY', 406, ['foo' => 'bar']);
    }

    public function provideExpectedSubscribedEventTypes()
    {
        return [
            [[KernelEvents::VIEW, KernelEvents::EXCEPTION]],
        ];
    }

    public function testOnKernelResultViewIsNotRestRequest()
    {
        $this->isRestRequest = false;

        $this->onKernelViewIsNotRestRequest(
            'onKernelResultView',
            $this->getControllerResultEvent()
        );
    }

    public function testOnKernelExceptionViewIsNotRestRequest()
    {
        $this->isRestRequest = false;

        $this->onKernelViewIsNotRestRequest(
            'onKernelExceptionView',
            $this->getExceptionEvent()
        );
    }

    protected function onKernelViewIsNotRestRequest($method, RequestEvent $event)
    {
        $this->getVisitorDispatcherMock()
            ->expects($this->never())
            ->method('dispatch');

        $this->getEventListener()->$method($event);
    }

    public function testOnKernelExceptionView()
    {
        $this->onKernelView('onKernelExceptionView', $this->getExceptionEvent(), $this->exceptionEventValue);
    }

    public function testOnControllerResultView()
    {
        $this->onKernelView('onKernelResultView', $this->getControllerResultEvent(), $this->eventValue);
    }

    protected function onKernelView($method, $event, $value)
    {
        $this->getVisitorDispatcherMock()
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->getRequestMock(),
                $value
            )->willReturn(
                $this->response
            );

        $this->getEventListener()->$method($event);

        $this->assertEquals($this->response, $event->getResponse());
    }

    /**
     * @return \Ibexa\Rest\Server\View\AcceptHeaderVisitorDispatcher|\PHPUnit\Framework\MockObject\MockObject
     */
    public function getVisitorDispatcherMock()
    {
        if (!isset($this->visitorDispatcherMock)) {
            $this->visitorDispatcherMock = $this->createMock(AcceptHeaderVisitorDispatcher::class);
        }

        return $this->visitorDispatcherMock;
    }

    /**
     * @return \Ibexa\Bundle\Rest\EventListener\ResponseListener
     */
    protected function getEventListener()
    {
        return new ResponseListener(
            $this->getVisitorDispatcherMock()
        );
    }

    /**
     * @return \Symfony\Component\HttpKernel\Event\ViewEvent
     */
    protected function getControllerResultEvent(): ViewEvent
    {
        if (!isset($this->event)) {
            $this->event = new ViewEvent(
                $this->getKernelMock(),
                $this->getRequestMock(),
                KernelInterface::MASTER_REQUEST,
                $this->eventValue
            );
        }

        return $this->event;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\HttpKernel\KernelInterface
     */
    protected function getKernelMock(): KernelInterface
    {
        if (!isset($this->kernelMock)) {
            $this->kernelMock = $this->createMock(KernelInterface::class);
        }

        return $this->kernelMock;
    }

    /**
     * @return \Symfony\Component\HttpKernel\Event\ExceptionEvent
     */
    protected function getExceptionEvent(): ExceptionEvent
    {
        if (!isset($this->event)) {
            $this->event = new ExceptionEvent(
                $this->getKernelMock(),
                $this->getRequestMock(),
                KernelInterface::MASTER_REQUEST,
                $this->exceptionEventValue
            );
        }

        return $this->event;
    }
}

class_alias(ResponseListenerTest::class, 'EzSystems\EzPlatformRestBundle\Tests\EventListener\ResponseListenerTest');
