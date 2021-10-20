<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use eZ\Publish\API\Repository\Values\ValueObject;

/**
 * Struct representing a freshly created location.
 */
class CreatedLocation extends ValueObject
{
    /**
     * The created location.
     *
     * @var \EzSystems\EzPlatformRest\Server\Values\RestLocation
     */
    public $restLocation;
}

class_alias(CreatedLocation::class, 'EzSystems\EzPlatformRest\Server\Values\CreatedLocation');
