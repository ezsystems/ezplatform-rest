<?php

/**
 * File containing the LocationList class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Values;

use EzSystems\EzPlatformRest\Value as RestValue;

/**
 * Location list view model.
 */
class LocationList extends RestValue
{
    /**
     * Locations.
     *
     * @var \EzSystems\EzPlatformRest\Server\Values\RestLocation[]
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
     * @param \EzSystems\EzPlatformRest\Server\Values\RestLocation[] $locations
     * @param string $path
     */
    public function __construct(array $locations, $path)
    {
        $this->locations = $locations;
        $this->path = $path;
    }
}
