<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

class ResourceCreated extends RestValue
{
    public function __construct($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }
}

class_alias(ResourceCreated::class, 'EzSystems\EzPlatformRest\Server\Values\ResourceCreated');
