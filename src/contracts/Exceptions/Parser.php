<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Contracts\Rest\Exceptions;

use InvalidArgumentException as PHPInvalidArgumentException;

/**
 * Exception thrown if a parser discovers an error.
 */
class Parser extends PHPInvalidArgumentException
{
}

class_alias(Parser::class, 'EzSystems\EzPlatformRest\Exceptions\Parser');
