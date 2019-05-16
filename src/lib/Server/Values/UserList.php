<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\EzPlatformRest\Server\Values;

use EzSystems\EzPlatformRest\Value as RestValue;

/**
 * User list view model.
 */
class UserList extends RestValue
{
    /**
     * Users.
     *
     * @var \EzSystems\EzPlatformRest\Server\Values\RestUser[]
     */
    public $users;

    /**
     * Path which was used to fetch the list of users.
     *
     * @var string
     */
    public $path;

    /**
     * Construct.
     *
     * @param \EzSystems\EzPlatformRest\Server\Values\RestUser[] $users
     * @param string $path
     */
    public function __construct(array $users, $path)
    {
        $this->users = $users;
        $this->path = $path;
    }
}
