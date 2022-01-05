<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Contracts\Rest\Output\Exceptions;

use RuntimeException;

/**
 * Output visiting invalid type exception.
 */
class InvalidTypeException extends RuntimeException
{
    /**
     * Construct from invalid data.
     *
     * @param mixed $data
     */
    public function __construct($data)
    {
        parent::__construct(
            'You must provide a ValueObject for visiting, "' . gettype($data) . '" provided.'
        );
    }
}

class_alias(InvalidTypeException::class, 'EzSystems\EzPlatformRest\Output\Exceptions\InvalidTypeException');
