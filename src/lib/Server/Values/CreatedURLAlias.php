<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\ValueObject;

/**
 * Struct representing a freshly created URLAlias.
 */
class CreatedURLAlias extends ValueObject
{
    /**
     * The created URL alias.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\URLAlias
     */
    public $urlAlias;
}

class_alias(CreatedURLAlias::class, 'EzSystems\EzPlatformRest\Server\Values\CreatedURLAlias');
