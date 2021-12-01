<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\ValueObject;

/**
 * Struct representing a freshly created field definition.
 */
class CreatedFieldDefinition extends ValueObject
{
    /**
     * The created field definition.
     *
     * @var \Ibexa\Rest\Server\Values\RestFieldDefinition
     */
    public $fieldDefinition;
}

class_alias(CreatedFieldDefinition::class, 'EzSystems\EzPlatformRest\Server\Values\CreatedFieldDefinition');
