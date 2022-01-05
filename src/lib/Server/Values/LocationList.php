<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

/**
 * Location list view model.
 */
class LocationList extends RestValue
{
    /**
     * Locations.
     *
     * @var \Ibexa\Rest\Server\Values\RestLocation[]
     */
    public $locations;

    /**
     * Path used to load this list of locations.
     *
     * @var string
     */
    public $path;

    /**
     * Construct.
     *
     * @param \Ibexa\Rest\Server\Values\RestLocation[] $locations
     * @param string $path
     */
    public function __construct(array $locations, $path)
    {
        $this->locations = $locations;
        $this->path = $path;
    }
}

class_alias(LocationList::class, 'EzSystems\EzPlatformRest\Server\Values\LocationList');
