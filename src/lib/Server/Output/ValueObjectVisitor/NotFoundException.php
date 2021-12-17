<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

/**
 * NotFoundException value object visitor.
 */
class NotFoundException extends Exception
{
    /**
     * Returns HTTP status code.
     *
     * @return int
     */
    protected function getStatus()
    {
        return 404;
    }
}

class_alias(NotFoundException::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\NotFoundException');
