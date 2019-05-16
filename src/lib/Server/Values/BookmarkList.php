<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Values;

use EzSystems\EzPlatformRest\Value as RestValue;

class BookmarkList extends RestValue
{
    /**
     * @var int
     */
    public $totalCount = 0;

    /**
     * @var \EzSystems\EzPlatformRest\Server\Values\RestLocation[]
     */
    public $items = [];

    /**
     * BookmarkList constructor.
     *
     * @param int $totalCount
     * @param \EzSystems\EzPlatformRest\Server\Values\RestLocation[] $items
     */
    public function __construct(int $totalCount, array $items)
    {
        $this->totalCount = $totalCount;
        $this->items = $items;
    }
}
