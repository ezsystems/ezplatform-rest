<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Values;

use Ibexa\Rest\Value as RestValue;

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

class_alias(Resource::class, 'EzSystems\EzPlatformRest\Values\Resource');
