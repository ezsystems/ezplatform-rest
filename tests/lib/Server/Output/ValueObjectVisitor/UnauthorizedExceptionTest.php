<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException;
use Ibexa\Rest\Server\Output\ValueObjectVisitor;

class UnauthorizedExceptionTest extends ExceptionTest
{
    /**
     * Get expected status code.
     *
     * @return int
     */
    protected function getExpectedStatusCode()
    {
        return 401;
    }

    /**
     * Get expected message.
     *
     * @return string
     */
    protected function getExpectedMessage()
    {
        return 'Unauthorized';
    }

    /**
     * Gets the exception.
     *
     * @return \Exception
     */
    protected function getException()
    {
        return $this->getMockForAbstractClass(UnauthorizedException::class);
    }

    /**
     * Gets the exception visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\UnauthorizedException
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\UnauthorizedException();
    }
}

class_alias(UnauthorizedExceptionTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\UnauthorizedExceptionTest');
