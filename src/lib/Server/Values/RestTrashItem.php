<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use eZ\Publish\API\Repository\Values\Content\TrashItem;
use Ibexa\Rest\Value as RestValue;

/**
 * RestTrashItem view model.
 */
class RestTrashItem extends RestValue
{
    /**
     * A trash item.
     *
     * @var \eZ\Publish\API\Repository\Values\Content\TrashItem
     */
    public $trashItem;

    /**
     * Number of children of the trash item.
     *
     * @var int
     */
    public $childCount;

    /**
     * @param \eZ\Publish\API\Repository\Values\Content\TrashItem $trashItem
     * @param int $childCount
     */
    public function __construct(TrashItem $trashItem, $childCount)
    {
        $this->trashItem = $trashItem;
        $this->childCount = $childCount;
    }
}

class_alias(RestTrashItem::class, 'EzSystems\EzPlatformRest\Server\Values\RestTrashItem');
