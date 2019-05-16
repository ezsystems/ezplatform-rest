<?php

/**
 * File containing the PolicyList class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Values;

use EzSystems\EzPlatformRest\Value as RestValue;

/**
 * Policy list view model.
 */
class PolicyList extends RestValue
{
    /**
     * Policies.
     *
     * @var \eZ\Publish\API\Repository\Values\User\Policy[]
     */
    public $policies;

    /**
     * Path which was used to fetch the list of policies.
     *
     * @var string
     */
    public $path;

    /**
     * Construct.
     *
     * @param \eZ\Publish\API\Repository\Values\User\Policy[] $policies
     * @param string $path
     */
    public function __construct(array $policies, $path)
    {
        $this->policies = $policies;
        $this->path = $path;
    }
}
