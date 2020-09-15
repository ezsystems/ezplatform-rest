<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Security\EventListener;

use eZ\Publish\API\Repository\PermissionResolver;
use eZ\Publish\Core\MVC\Symfony\Security\UserInterface as EzPlatformUser;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent as BaseInteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * This security listener listens to security.interactive_login event to:
 *  - Set current user reference if user is an instance of an eZ user.
 */
final class SecurityListener implements EventSubscriberInterface
{
    /** @var \eZ\Publish\API\Repository\PermissionResolver */
    private $permissionResolver;

    public function __construct(
        PermissionResolver $permissionResolver
    ) {
        $this->permissionResolver = $permissionResolver;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => [
                ['onInteractiveLogin', 10],
            ],
        ];
    }

    public function onInteractiveLogin(BaseInteractiveLoginEvent $event): void
    {
        $token = $event->getAuthenticationToken();

        if (!$token instanceof JWTUserToken) {
            return;
        }

        $user = $event->getAuthenticationToken()->getUser();
        if ($user instanceof EzPlatformUser) {
            $this->permissionResolver->setCurrentUserReference($user->getAPIUser());
        }
    }
}
