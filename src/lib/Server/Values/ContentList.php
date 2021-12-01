<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

/**
 * Content list view model.
 */
class ContentList extends RestValue
{
    /**
     * Contents.
     *
     * @var \Ibexa\Rest\Server\Values\RestContent[]
     */
    public $contents;

    /**
     * Total items list count.
     *
     * @var int
     */
    public $totalCount;

    /**
     * Construct.
     *
     * @param \Ibexa\Rest\Server\Values\RestContent[] $contents
     * @param int $totalCount
     */
    public function __construct(array $contents, int $totalCount)
    {
        $this->contents = $contents;
        $this->totalCount = $totalCount;
    }
}

class_alias(ContentList::class, 'EzSystems\EzPlatformRest\Server\Values\ContentList');
