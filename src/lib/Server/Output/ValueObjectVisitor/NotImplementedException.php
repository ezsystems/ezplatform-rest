<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

/**
 * NotImplementedException value object visitor.
 */
class NotImplementedException extends Exception
{
    /**
     * Returns HTTP status code.
     *
     * @return int
     */
    protected function getStatus()
    {
        return 501;
    }
}

class_alias(NotImplementedException::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\NotImplementedException');
