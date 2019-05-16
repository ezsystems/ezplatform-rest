<?php

/**
 * File containing the RootResourceBuilderInterface class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Service;

interface RootResourceBuilderInterface
{
    /**
     * Build root resource.
     *
     * @return array|\EzSystems\EzPlatformRest\Values\Root
     */
    public function buildRootResource();
}
