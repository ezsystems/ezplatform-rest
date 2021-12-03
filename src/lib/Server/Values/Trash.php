<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

/**
 * Trash view model.
 */
class Trash extends RestValue
{
    /**
     * Trash items.
     *
     * @var \Ibexa\Rest\Server\Values\RestTrashItem[]
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
     * @param \Ibexa\Rest\Server\Values\RestTrashItem[] $trashItems
     * @param string $path
     */
    public function __construct(array $trashItems, $path)
    {
        $this->trashItems = $trashItems;
        $this->path = $path;
    }
}

class_alias(Trash::class, 'EzSystems\EzPlatformRest\Server\Values\Trash');
