<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Contracts\Rest\Output\Exceptions;

use RuntimeException;

/**
 * Invalid output generation.
 */
class OutputGeneratorException extends RuntimeException
{
    /**
     * Construct from error message.
     *
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct(
            'Output visiting failed: ' . $message
        );
    }
}

class_alias(OutputGeneratorException::class, 'EzSystems\EzPlatformRest\Output\Exceptions\OutputGeneratorException');
