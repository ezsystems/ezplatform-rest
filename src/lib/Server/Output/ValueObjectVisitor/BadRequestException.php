<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

/**
 * BadRequestException value object visitor.
 */
class BadRequestException extends Exception
{
    /**
     * Returns HTTP status code.
     *
     * @return int
     */
    protected function getStatus()
    {
        return 400;
    }
}

class_alias(BadRequestException::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\BadRequestException');
