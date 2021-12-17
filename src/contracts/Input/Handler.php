<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Contracts\Rest\Input;

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

class_alias(Handler::class, 'EzSystems\EzPlatformRest\Input\Handler');
