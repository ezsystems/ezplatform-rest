<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\ValueObject;

/**
 * Struct representing a freshly created policy.
 */
class CreatedPolicy extends ValueObject
{
    /**
     * The created policy.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\User\Policy
     */
    public $policy;
}

class_alias(CreatedPolicy::class, 'EzSystems\EzPlatformRest\Server\Values\CreatedPolicy');
