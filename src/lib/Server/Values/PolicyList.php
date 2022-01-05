<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

/**
 * Policy list view model.
 */
class PolicyList extends RestValue
{
    /**
     * Policies.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\User\Policy[]
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
     * @param \Ibexa\Contracts\Core\Repository\Values\User\Policy[] $policies
     * @param string $path
     */
    public function __construct(array $policies, $path)
    {
        $this->policies = $policies;
        $this->path = $path;
    }
}

class_alias(PolicyList::class, 'EzSystems\EzPlatformRest\Server\Values\PolicyList');
