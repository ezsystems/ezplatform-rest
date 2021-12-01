<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

/**
 * BadStateException value object visitor.
 */
class BadStateException extends Exception
{
    /**
     * Returns HTTP status code.
     *
     * @return int
     */
    protected function getStatus()
    {
        return 409;
    }
}

class_alias(BadStateException::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\BadStateException');
