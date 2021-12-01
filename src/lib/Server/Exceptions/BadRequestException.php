<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Exceptions;

use InvalidArgumentException;

/**
 * Exception thrown if the request is not formatted correctly.
 */
class BadRequestException extends InvalidArgumentException
{
}

class_alias(BadRequestException::class, 'EzSystems\EzPlatformRest\Server\Exceptions\BadRequestException');
