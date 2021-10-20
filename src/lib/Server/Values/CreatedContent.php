<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use eZ\Publish\API\Repository\Values\ValueObject;

/**
 * Struct representing a freshly created Content.
 */
class CreatedContent extends ValueObject
{
    /**
     * The created content.
     *
     * @var \EzSystems\EzPlatformRest\Server\Values\RestContent
     */
    public $content;
}

class_alias(CreatedContent::class, 'EzSystems\EzPlatformRest\Server\Values\CreatedContent');
