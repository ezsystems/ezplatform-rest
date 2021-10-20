<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use eZ\Publish\API\Repository\Values\ValueObject;

/**
 * Struct representing a freshly created version.
 */
class CreatedVersion extends ValueObject
{
    /**
     * The created version.
     *
     * @var \EzSystems\EzPlatformRest\Server\Values\Version
     */
    public $version;
}

class_alias(CreatedVersion::class, 'EzSystems\EzPlatformRest\Server\Values\CreatedVersion');
