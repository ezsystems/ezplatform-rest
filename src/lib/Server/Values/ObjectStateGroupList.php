<?php

/**
 * File containing the ObjectStateGroupList class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Values;

use EzSystems\EzPlatformRest\Value as RestValue;

/**
 * ObjectStateGroup list view model.
 */
class ObjectStateGroupList extends RestValue
{
    /**
     * Object state groups.
     *
     * @var \eZ\Publish\API\Repository\Values\ObjectState\ObjectStateGroup[]
     */
    public $groups;

    /**
     * Construct.
     *
     * @param \eZ\Publish\API\Repository\Values\ObjectState\ObjectStateGroup[] $groups
     */
    public function __construct(array $groups)
    {
        $this->groups = $groups;
    }
}
