<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Values;

use eZ\Publish\API\Repository\Values\ValueObject;

/**
 * Struct representing a freshly created section.
 */
class CreatedSection extends ValueObject
{
    /**
     * The created section.
     *
     * @var \eZ\Publish\API\Repository\Values\Content\Section
     */
    public $section;
}
