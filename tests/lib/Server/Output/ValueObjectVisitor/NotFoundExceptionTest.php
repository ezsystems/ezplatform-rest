<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException;
use Ibexa\Rest\Server\Output\ValueObjectVisitor;

class NotFoundExceptionTest extends ExceptionTest
{
    /**
     * Get expected status code.
     *
     * @return int
     */
    protected function getExpectedStatusCode()
    {
        return 404;
    }

    /**
     * Get expected message.
     *
     * @return string
     */
    protected function getExpectedMessage()
    {
        return 'Not Found';
    }

    /**
     * Get the exception.
     *
     * @return \Exception
     */
    protected function getException()
    {
        return $this->getMockForAbstractClass(NotFoundException::class);
    }

    /**
     * Get the exception visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\NotFoundException
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\NotFoundException();
    }
}

class_alias(NotFoundExceptionTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\NotFoundExceptionTest');
