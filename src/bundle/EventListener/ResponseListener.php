<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Bundle\Rest\EventListener;

use Ibexa\Rest\Server\View\AcceptHeaderVisitorDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * REST Response Listener.
 *
 * Converts responses from REST controllers to REST Responses, depending on the Accept-Header value.
 */
class ResponseListener implements EventSubscriberInterface
{
    /**
     * @var \Ibexa\Rest\Server\View\AcceptHeaderVisitorDispatcher
     */
    private $viewDispatcher;

    /**
     * @param $viewDispatcher AcceptHeaderVisitorDispatcher
     */
    public function __construct(AcceptHeaderVisitorDispatcher $viewDispatcher)
    {
        $this->viewDispatcher = $viewDispatcher;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => 'onKernelResultView',
            // Must happen BEFORE the Core ExceptionListener.
            KernelEvents::EXCEPTION => ['onKernelExceptionView', 20],
        ];
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ViewEvent $event
     */
    public function onKernelResultView(ViewEvent $event)
    {
        if (!$event->getRequest()->attributes->get('is_rest_request')) {
            return;
        }

        $event->setResponse(
            $this->viewDispatcher->dispatch(
                $event->getRequest(),
                $event->getControllerResult()
            )
        );
        $event->stopPropagation();
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ExceptionEvent $event
     *
     * @throws \Exception
     */
    public function onKernelExceptionView(ExceptionEvent $event)
    {
        if (!$event->getRequest()->attributes->get('is_rest_request')) {
            return;
        }

        $event->setResponse(
            $this->viewDispatcher->dispatch(
                $event->getRequest(),
                $event->getThrowable()
            )
        );
        $event->stopPropagation();
    }
}

class_alias(ResponseListener::class, 'EzSystems\EzPlatformRestBundle\EventListener\ResponseListener');
