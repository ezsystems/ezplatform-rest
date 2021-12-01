<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\ValueObject;

/**
 * Struct representing a freshly created ContentTypeGroup.
 */
class CreatedContentTypeGroup extends ValueObject
{
    /**
     * The created content type group.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentTypeGroup
     */
    public $contentTypeGroup;
}

class_alias(CreatedContentTypeGroup::class, 'EzSystems\EzPlatformRest\Server\Values\CreatedContentTypeGroup');
