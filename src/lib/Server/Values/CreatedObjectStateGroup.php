<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\ValueObject;

/**
 * Struct representing a freshly created object state group.
 */
class CreatedObjectStateGroup extends ValueObject
{
    /**
     * The created object state group.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\ObjectState\ObjectStateGroup
     */
    public $objectStateGroup;
}

class_alias(CreatedObjectStateGroup::class, 'EzSystems\EzPlatformRest\Server\Values\CreatedObjectStateGroup');
