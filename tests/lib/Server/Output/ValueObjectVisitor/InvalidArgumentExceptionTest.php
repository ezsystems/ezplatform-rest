<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Rest\Server\Output\ValueObjectVisitor;

class InvalidArgumentExceptionTest extends ExceptionTest
{
    /**
     * Get expected status code.
     *
     * @return int
     */
    protected function getExpectedStatusCode()
    {
        return 406;
    }

    /**
     * Get expected message.
     *
     * @return string
     */
    protected function getExpectedMessage()
    {
        return 'Not Acceptable';
    }

    /**
     * Gets the exception.
     *
     * @return \Exception
     */
    protected function getException()
    {
        return new Exceptions\InvalidArgumentException('Test');
    }

    /**
     * Gets the exception visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\InvalidArgumentException
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\InvalidArgumentException();
    }
}

class_alias(InvalidArgumentExceptionTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\InvalidArgumentExceptionTest');
