<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\User\UserGroupRoleAssignment;
use Ibexa\Rest\Value as RestValue;

/**
 * RestUserGroupRoleAssignment view model.
 */
class RestUserGroupRoleAssignment extends RestValue
{
    /**
     * Role assignment.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\User\UserGroupRoleAssignment
     */
    public $roleAssignment;

    /**
     * User group ID to which the role is assigned.
     *
     * @var mixed
     */
    public $id;

    /**
     * Construct.
     *
     * @param \Ibexa\Contracts\Core\Repository\Values\User\UserGroupRoleAssignment $roleAssignment
     * @param mixed $id
     */
    public function __construct(UserGroupRoleAssignment $roleAssignment, $id)
    {
        $this->roleAssignment = $roleAssignment;
        $this->id = $id;
    }
}

class_alias(RestUserGroupRoleAssignment::class, 'EzSystems\EzPlatformRest\Server\Values\RestUserGroupRoleAssignment');
