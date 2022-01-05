<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Output\Generator\Json;

use ArrayObject as NativeArrayObject;

/**
 * Json array object.
 *
 * Special JSON array object implementation, which allows to access the
 * parent object it is assigned to again.
 */
class ArrayObject extends NativeArrayObject
{
    /**
     * Reference to the parent node.
     *
     * @var \Ibexa\Rest\Output\Generator\Json\JsonObject
     */
    protected $_ref_parent;

    /**
     * Construct from optional parent node.
     *
     * @param mixed $_ref_parent
     */
    public function __construct($_ref_parent)
    {
        $this->_ref_parent = $_ref_parent;
    }

    /**
     * Get Parent of current node.
     *
     * @return \Ibexa\Rest\Output\Generator\Json\JsonObject
     */
    public function getParent()
    {
        return $this->_ref_parent;
    }
}

class_alias(ArrayObject::class, 'EzSystems\EzPlatformRest\Output\Generator\Json\ArrayObject');
