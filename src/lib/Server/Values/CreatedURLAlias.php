<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use eZ\Publish\API\Repository\Values\ValueObject;

/**
 * Struct representing a freshly created URLAlias.
 */
class CreatedURLAlias extends ValueObject
{
    /**
     * The created URL alias.
     *
     * @var \eZ\Publish\API\Repository\Values\Content\URLAlias
     */
    public $urlAlias;
}

class_alias(CreatedURLAlias::class, 'EzSystems\EzPlatformRest\Server\Values\CreatedURLAlias');
