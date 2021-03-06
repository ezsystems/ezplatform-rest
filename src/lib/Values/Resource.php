<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Values;

use EzSystems\EzPlatformRest\Value as RestValue;

/**
 * This class represents a resource.
 */
class Resource extends RestValue
{
    /**
     * Resource name.
     *
     * @var string
     */
    public $name;

    /**
     * Media Type of the resource.
     *
     * @var string
     */
    public $mediaType;

    /**
     * href of the resource.
     *
     * @var string
     */
    public $href;

    /**
     * Resource constructor.
     *
     * @param $name
     * @param $mediaType
     * @param $href
     */
    public function __construct($name, $mediaType, $href)
    {
        $this->name = $name;
        $this->mediaType = $mediaType;
        $this->href = $href;
    }
}
