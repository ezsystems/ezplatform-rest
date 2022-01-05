<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\Visitor;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class HttpException extends Exception
{
    protected function generateErrorCode(Generator $generator, Visitor $visitor, \Exception $e): int
    {
        $statusCode = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : $this->getStatus();
        $visitor->setStatus($statusCode);
        $generator->valueElement('errorCode', $statusCode);

        return $statusCode;
    }
}

class_alias(HttpException::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\HttpException');
