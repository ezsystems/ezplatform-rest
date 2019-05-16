<?php

/**
 * File containing the Handler base class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Input;

/**
 * Input format handler base class.
 */
abstract class Handler
{
    /**
     * Converts the given string to an array structure.
     *
     * @param string $string
     *
     * @return array
     */
    abstract public function convert($string);
}
