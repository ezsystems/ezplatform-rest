<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Values;

use EzSystems\EzPlatformRest\Value as RestValue;

/**
 * Trash view model.
 */
class Trash extends RestValue
{
    /**
     * Trash items.
     *
     * @var \EzSystems\EzPlatformRest\Server\Values\RestTrashItem[]
     */
    public $trashItems;

    /**
     * Path used to load the list of the trash items.
     *
     * @var string
     */
    public $path;

    /**
     * Construct.
     *
     * @param \EzSystems\EzPlatformRest\Server\Values\RestTrashItem[] $trashItems
     * @param string $path
     */
    public function __construct(array $trashItems, $path)
    {
        $this->trashItems = $trashItems;
        $this->path = $path;
    }
}
