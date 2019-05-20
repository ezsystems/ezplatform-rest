<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Values;

use EzSystems\EzPlatformRest\Value as RestValue;

/**
 * User group list view model.
 */
class UserGroupRefList extends RestValue
{
    /**
     * User groups.
     *
     * @var \EzSystems\EzPlatformRest\Server\Values\RestUserGroup[]
     */
    public $userGroups;

    /**
     * Path which was used to fetch the list of user groups.
     *
     * @var string
     */
    public $path;

    /**
     * User ID whose groups are the ones in the list.
     *
     * @var mixed
     */
    public $userId;

    /**
     * Construct.
     *
     * @param \EzSystems\EzPlatformRest\Server\Values\RestUserGroup[] $userGroups
     * @param string $path
     * @param mixed $userId
     */
    public function __construct(array $userGroups, $path, $userId = null)
    {
        $this->userGroups = $userGroups;
        $this->path = $path;
        $this->userId = $userId;
    }
}
