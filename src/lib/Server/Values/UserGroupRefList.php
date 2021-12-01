<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

/**
 * User group list view model.
 */
class UserGroupRefList extends RestValue
{
    /**
     * User groups.
     *
     * @var \Ibexa\Rest\Server\Values\RestUserGroup[]
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
     * @param \Ibexa\Rest\Server\Values\RestUserGroup[] $userGroups
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

class_alias(UserGroupRefList::class, 'EzSystems\EzPlatformRest\Server\Values\UserGroupRefList');
