<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Bundle\Rest\EventListener;

use Ibexa\Bundle\Rest\RestEvents;
use Ibexa\Core\Base\Exceptions\UnauthorizedException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CsrfListener implements EventSubscriberInterface
{
    /**
     * Name of the HTTP header containing CSRF token.
     */
    public const CSRF_TOKEN_HEADER = 'X-CSRF-Token';

    /**
     * @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface|null
     */
    private $csrfTokenManager;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var bool
     */
    private $csrfEnabled;

    /**
     * @var bool
     */
    private $csrfTokenIntention;

    /**
     * Note that CSRF provider needs to be optional as it will not be available
     * when CSRF protection is disabled.
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param bool $csrfEnabled
     * @param string $csrfTokenIntention
     * @param \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface|null $csrfTokenManager
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        $csrfEnabled,
        $csrfTokenIntention,
        CsrfTokenManagerInterface $csrfTokenManager = null
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->csrfEnabled = $csrfEnabled;
        $this->csrfTokenIntention = $csrfTokenIntention;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    /**
     * This method validates CSRF token if CSRF protection is enabled.
     *
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     *
     * @throws \Ibexa\Core\Base\Exceptions\UnauthorizedException
     */
    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->getRequest()->attributes->get('is_rest_request')) {
            return;
        }

        if (!$this->csrfEnabled) {
            return;
        }

        // skip CSRF validation if no session is running
        if (!$event->getRequest()->getSession()->isStarted()) {
            return;
        }

        if ($this->isMethodSafe($event->getRequest()->getMethod())) {
            return;
        }

        if ($this->isSessionRoute($event->getRequest()->get('_route'))) {
            return;
        }

        if (!$event->getRequest()->attributes->getBoolean('csrf_protection', true)) {
            return;
        }

        if (!$this->checkCsrfToken($event->getRequest())) {
            throw new UnauthorizedException(
                'Missing or invalid CSRF token',
                $event->getRequest()->getMethod() . ' ' . $event->getRequest()->getPathInfo()
            );
        }

        // Dispatching event so that CSRF token intention can be injected into Legacy Stack
        $this->eventDispatcher->dispatch($event, RestEvents::REST_CSRF_TOKEN_VALIDATED);
    }

    /**
     * @param string $method
     *
     * @return bool
     */
    protected function isMethodSafe($method)
    {
        return in_array($method, ['GET', 'HEAD', 'OPTIONS']);
    }

    /**
     * @param string $route
     *
     * @return bool
     *
     * @deprecated Deprecated since 6.5. Use isSessionRoute() instead.
     */
    protected function isLoginRequest($route)
    {
        return $route === 'ezpublish_rest_createSession';
    }

    /**
     * Tests if a given $route is a session management one.
     *
     * @param string $route
     *
     * @return bool
     *
     * @deprecated since Ibexa DXP 3.3.7. Add csrf_protection: false attribute to route definition instead.
     */
    protected function isSessionRoute($route)
    {
        return in_array(
            $route,
            ['ezpublish_rest_createSession', 'ezpublish_rest_refreshSession', 'ezpublish_rest_deleteSession']
        );
    }

    /**
     * Checks the validity of the request's csrf token header.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool true/false if the token is valid/invalid, false if none was found in the request's headers
     */
    protected function checkCsrfToken(Request $request)
    {
        if (!$request->headers->has(self::CSRF_TOKEN_HEADER)) {
            return false;
        }

        return $this->csrfTokenManager->isTokenValid(
            new CsrfToken(
                $this->csrfTokenIntention,
                $request->headers->get(self::CSRF_TOKEN_HEADER)
            )
        );
    }
}

class_alias(CsrfListener::class, 'EzSystems\EzPlatformRestBundle\EventListener\CsrfListener');
