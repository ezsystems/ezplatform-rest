<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

/**
 * ContentType list view model.
 */
class ContentTypeInfoList extends RestValue
{
    /**
     * Content types.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType[]
     */
    public $contentTypes;

    /**
     * Path which was used to fetch the list of content types.
     *
     * @var string
     */
    public $path;

    /**
     * Construct.
     *
     * @param \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType[] $contentTypes
     * @param string $path
     */
    public function __construct(array $contentTypes, $path)
    {
        $this->contentTypes = $contentTypes;
        $this->path = $path;
    }
}

class_alias(ContentTypeInfoList::class, 'EzSystems\EzPlatformRest\Server\Values\ContentTypeInfoList');
