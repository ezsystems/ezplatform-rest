<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\Content\Location;
use Ibexa\Rest\Value as RestValue;

/**
 * RestLocation view model.
 */
class RestLocation extends RestValue
{
    /**
     * A location.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\Location
     */
    public $location;

    /**
     * Number of children of the location.
     *
     * @var int
     */
    public $childCount;

    /**
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Location $location
     * @param int $childCount
     */
    public function __construct(Location $location, $childCount)
    {
        $this->location = $location;
        $this->childCount = $childCount;
    }
}

class_alias(RestLocation::class, 'EzSystems\EzPlatformRest\Server\Values\RestLocation');
