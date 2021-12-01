<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

/**
 * RoleAssignment list view model.
 */
class RoleAssignmentList extends RestValue
{
    /**
     * Role assignments.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\User\RoleAssignment[]
     */
    public $roleAssignments;

    /**
     * User or user group ID to which the roles are assigned.
     *
     * @var mixed
     */
    public $id;

    /**
     * Indicator if the role assignment is for user group.
     *
     * @var bool
     */
    public $isGroupAssignment;

    /**
     * Construct.
     *
     * @param \Ibexa\Contracts\Core\Repository\Values\User\RoleAssignment[] $roleAssignments
     * @param mixed $id
     * @param bool $isGroupAssignment
     */
    public function __construct(array $roleAssignments, $id, $isGroupAssignment = false)
    {
        $this->roleAssignments = $roleAssignments;
        $this->id = $id;
        $this->isGroupAssignment = $isGroupAssignment;
    }
}

class_alias(RoleAssignmentList::class, 'EzSystems\EzPlatformRest\Server\Values\RoleAssignmentList');
