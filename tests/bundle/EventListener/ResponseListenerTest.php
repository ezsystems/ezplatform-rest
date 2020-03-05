<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRestBundle\Tests\EventListener;

use Exception;
use EzSystems\EzPlatformRest\Server\View\AcceptHeaderVisitorDispatcher;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use EzSystems\EzPlatformRestBundle\EventListener\ResponseListener;
use stdClass;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\EventDispatcher\Event;

class ResponseListenerTest extends EventListenerTest
{
    /** @var AcceptHeaderVisitorDispatcher|MockObject */
    protected $visitorDispatcherMock;

    /** @var \stdClass */
    protected $eventValue;

    /** @var Exception */
    protected $exceptionEventValue;

    protected $dispatcherMessage;

    protected $controllerResult;

    /** @var Response */
    protected $response;

    /** @var Event */
    protected $event;

    /** @var KernelInterface|MockObject */
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
     * @return AcceptHeaderVisitorDispatcher|MockObject
     */
    public function getVisitorDispatcherMock()
    {
        if (!isset($this->visitorDispatcherMock)) {
            $this->visitorDispatcherMock = $this->createMock(AcceptHeaderVisitorDispatcher::class);
        }

        return $this->visitorDispatcherMock;
    }

    /**
     * @return ResponseListener
     */
    protected function getEventListener()
    {
        return new ResponseListener(
            $this->getVisitorDispatcherMock()
        );
    }

    /**
     * @return ViewEvent
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
     * @return MockObject|KernelInterface
     */
    protected function getKernelMock(): KernelInterface
    {
        if (!isset($this->kernelMock)) {
            $this->kernelMock = $this->createMock(KernelInterface::class);
        }

        return $this->kernelMock;
    }

    /**
     * @return ExceptionEvent
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
