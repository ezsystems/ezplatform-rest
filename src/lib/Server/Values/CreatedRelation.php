<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\ValueObject;

/**
 * Struct representing a freshly created relation.
 */
class CreatedRelation extends ValueObject
{
    /**
     * The created relation.
     *
     * @var \Ibexa\Rest\Server\Values\RestRelation
     */
    public $relation;
}

class_alias(CreatedRelation::class, 'EzSystems\EzPlatformRest\Server\Values\CreatedRelation');
