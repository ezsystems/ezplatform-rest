<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Values;

use Ibexa\Contracts\Core\Repository\Values\Content\ContentMetadataUpdateStruct;

/**
 * Extended ContentMetadataUpdateStruct that includes section information.
 */
class RestContentMetadataUpdateStruct extends ContentMetadataUpdateStruct
{
    /**
     * ID of the section to assign.
     *
     * Leave null to not change section assignment.
     *
     * @var mixed
     */
    public $sectionId;
}

class_alias(RestContentMetadataUpdateStruct::class, 'EzSystems\EzPlatformRest\Values\RestContentMetadataUpdateStruct');
