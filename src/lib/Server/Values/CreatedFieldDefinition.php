<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use eZ\Publish\API\Repository\Values\ValueObject;

/**
 * Struct representing a freshly created field definition.
 */
class CreatedFieldDefinition extends ValueObject
{
    /**
     * The created field definition.
     *
     * @var \EzSystems\EzPlatformRest\Server\Values\RestFieldDefinition
     */
    public $fieldDefinition;
}

class_alias(CreatedFieldDefinition::class, 'EzSystems\EzPlatformRest\Server\Values\CreatedFieldDefinition');
