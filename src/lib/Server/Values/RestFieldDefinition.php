<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Values;

use eZ\Publish\API\Repository\Values\ContentType\ContentType;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;
use EzSystems\EzPlatformRest\Value as RestValue;

/**
 * RestFieldDefinition view model.
 */
class RestFieldDefinition extends RestValue
{
    /**
     * ContentType the field definitions belong to.
     *
     * @var \eZ\Publish\API\Repository\Values\ContentType\ContentType
     */
    public $contentType;

    /**
     * Field definition.
     *
     * @var \eZ\Publish\API\Repository\Values\ContentType\FieldDefinition
     */
    public $fieldDefinition;

    /**
     * Path which was used to fetch the list of field definition.
     *
     * @var string|null
     */
    public $path;

    public function __construct(ContentType $contentType, FieldDefinition $fieldDefinition, ?string $path = null)
    {
        $this->contentType = $contentType;
        $this->fieldDefinition = $fieldDefinition;
        $this->path = $path;
    }
}
