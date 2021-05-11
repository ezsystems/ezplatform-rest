<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\Visitor;
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
