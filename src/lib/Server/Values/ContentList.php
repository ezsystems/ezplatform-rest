<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Values;

use EzSystems\EzPlatformRest\Value as RestValue;

/**
 * Content list view model.
 */
class ContentList extends RestValue
{
    /**
     * Contents.
     *
     * @var \EzSystems\EzPlatformRest\Server\Values\RestContent[]
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
     * @param \EzSystems\EzPlatformRest\Server\Values\RestContent[] $contents
     * @param int $totalCount
     */
    public function __construct(array $contents, int $totalCount)
    {
        $this->contents = $contents;
        $this->totalCount = $totalCount;
    }
}
