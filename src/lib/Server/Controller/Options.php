<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Controller;

use Ibexa\Rest\Server\Controller as RestController;
use Ibexa\Rest\Server\Values;

/**
 * Root controller.
 */
class Options extends RestController
{
    /**
     * Lists the verbs available for a resource.
     *
     * @param $allowedMethods string comma separated list of supported methods. Depends on the matched OPTIONS route.
     *
     * @return Values\Options
     */
    public function getRouteOptions($allowedMethods)
    {
        return new Values\Options(explode(',', $allowedMethods));
    }
}

class_alias(Options::class, 'EzSystems\EzPlatformRest\Server\Controller\Options');
