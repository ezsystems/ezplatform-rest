<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType;
use Ibexa\Contracts\Core\Repository\Values\ContentType\FieldDefinition;
use Ibexa\Rest\Value as RestValue;

/**
 * RestFieldDefinition view model.
 */
class RestFieldDefinition extends RestValue
{
    /**
     * ContentType the field definitions belong to.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType
     */
    public $contentType;

    /**
     * Field definition.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\ContentType\FieldDefinition
     */
    public $fieldDefinition;

    /**
     * Construct.
     *
     * @param \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType $contentType
     * @param \Ibexa\Contracts\Core\Repository\Values\ContentType\FieldDefinition $fieldDefinition
     */
    public function __construct(ContentType $contentType, FieldDefinition $fieldDefinition)
    {
        $this->contentType = $contentType;
        $this->fieldDefinition = $fieldDefinition;
    }
}

class_alias(RestFieldDefinition::class, 'EzSystems\EzPlatformRest\Server\Values\RestFieldDefinition');
