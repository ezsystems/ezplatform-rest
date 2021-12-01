<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Exceptions;

use InvalidArgumentException;

/**
 * Exception thrown if authentication credentials were provided by the
 * authentication failed.
 */
class AuthenticationFailedException extends InvalidArgumentException
{
}

class_alias(AuthenticationFailedException::class, 'EzSystems\EzPlatformRest\Server\Exceptions\AuthenticationFailedException');
