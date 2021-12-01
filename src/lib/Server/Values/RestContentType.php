<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType;
use Ibexa\Rest\Value as RestValue;

/**
 * REST Content type, as received by /content/types/<ID>.
 *
 * Might have a "FieldDefinitionList" embedded
 */
class RestContentType extends RestValue
{
    /**
     * @var \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType
     */
    public $contentType;

    /**
     * @var \Ibexa\Contracts\Core\Repository\Values\ContentType\FieldDefinition[]
     */
    public $fieldDefinitions;

    /**
     * Construct.
     *
     * @param \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType $contentType
     * @param \Ibexa\Contracts\Core\Repository\Values\ContentType\FieldDefinition[] $fieldDefinitions
     */
    public function __construct(ContentType $contentType, array $fieldDefinitions = null)
    {
        $this->contentType = $contentType;
        $this->fieldDefinitions = $fieldDefinitions;
    }
}

class_alias(RestContentType::class, 'EzSystems\EzPlatformRest\Server\Values\RestContentType');
