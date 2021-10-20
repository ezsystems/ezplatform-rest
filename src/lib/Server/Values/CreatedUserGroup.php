<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use eZ\Publish\API\Repository\Values\ValueObject;

/**
 * Struct representing a freshly created UserGroup.
 */
class CreatedUserGroup extends ValueObject
{
    /**
     * The created user group.
     *
     * @var \EzSystems\EzPlatformRest\Server\Values\RestUserGroup
     */
    public $userGroup;
}

class_alias(CreatedUserGroup::class, 'EzSystems\EzPlatformRest\Server\Values\CreatedUserGroup');
