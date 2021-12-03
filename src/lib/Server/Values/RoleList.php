<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

/**
 * Role list view model.
 */
class RoleList extends RestValue
{
    /**
     * Roles.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\User\Role[]
     */
    public $roles;

    /**
     * Path used to load the list of roles.
     *
     * @var string
     */
    public $path;

    /**
     * Construct.
     *
     * @param \Ibexa\Contracts\Core\Repository\Values\User\Role[] $roles
     * @param string $path
     */
    public function __construct(array $roles, $path)
    {
        $this->roles = $roles;
        $this->path = $path;
    }
}

class_alias(RoleList::class, 'EzSystems\EzPlatformRest\Server\Values\RoleList');
