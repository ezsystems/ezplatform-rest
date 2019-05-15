<?php

/**
 * File containing the ContentList class.
 *
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
     * Construct.
     *
     * @param \EzSystems\EzPlatformRest\Server\Values\RestContent[] $contents
     */
    public function __construct(array $contents)
    {
        $this->contents = $contents;
    }
}
