<?php

/**
 * File containing the RestLocationUpdateStruct class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Values;

use eZ\Publish\API\Repository\Values\Content\LocationUpdateStruct;
use EzSystems\EzPlatformRest\Value as RestValue;

/**
 * RestLocationUpdateStruct view model.
 */
class RestLocationUpdateStruct extends RestValue
{
    /**
     * Location update struct.
     *
     * @var \eZ\Publish\API\Repository\Values\Content\LocationUpdateStruct
     */
    public $locationUpdateStruct;

    /**
     * If set, the location is hidden ( == true ) or unhidden ( == false ).
     *
     * @var bool
     */
    public $hidden;

    /**
     * Construct.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\LocationUpdateStruct $locationUpdateStruct
     * @param bool $hidden
     */
    public function __construct(LocationUpdateStruct $locationUpdateStruct, $hidden = null)
    {
        $this->locationUpdateStruct = $locationUpdateStruct;
        $this->hidden = $hidden;
    }
}
