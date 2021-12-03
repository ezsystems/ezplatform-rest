<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\User\User;
use Ibexa\Rest\Value as RestValue;

/**
 * User list view model.
 */
class UserSession extends RestValue
{
    /**
     * User.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\User\User
     */
    public $user;

    /**
     * Session name.
     *
     * @var string
     */
    public $sessionName;

    /**
     * Session ID.
     *
     * @var string
     */
    public $sessionId;

    /**
     * CSRF token value.
     *
     * @var string
     */
    public $csrfToken;

    /**
     * True if session exists.
     *
     * @var bool
     */
    public $exists;

    /**
     * @param \Ibexa\Contracts\Core\Repository\Values\User\User $user
     * @param string $sessionName
     * @param string $sessionId
     * @param string $csrfToken
     */
    public function __construct(User $user, $sessionName, $sessionId, $csrfToken, $created)
    {
        $this->user = $user;
        $this->sessionName = $sessionName;
        $this->sessionId = $sessionId;
        $this->csrfToken = $csrfToken;
        $this->created = $created;
    }
}

class_alias(UserSession::class, 'EzSystems\EzPlatformRest\Server\Values\UserSession');
