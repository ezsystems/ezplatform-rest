<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\EzPlatformRest\Server\Controller;

use EzSystems\EzPlatformRest\Server\Values;
use EzSystems\EzPlatformRest\Server\Controller as RestController;

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
