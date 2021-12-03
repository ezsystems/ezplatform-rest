<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\ValueObject;

/**
 * Struct representing a freshly created object state.
 */
class CreatedObjectState extends ValueObject
{
    /**
     * The created object state.
     *
     * @var \Ibexa\Rest\Values\RestObjectState
     */
    public $objectState;
}

class_alias(CreatedObjectState::class, 'EzSystems\EzPlatformRest\Server\Values\CreatedObjectState');
