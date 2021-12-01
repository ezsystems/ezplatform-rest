<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Contracts\Rest\Exceptions;

use Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException as APINotFoundException;

/**
 * Implementation of the {@link \eZ\Publish\API\Repository\Exceptions\NotFoundException}
 * interface.
 *
 * @see \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
 */
class NotFoundException extends APINotFoundException
{
}

class_alias(NotFoundException::class, 'EzSystems\EzPlatformRest\Exceptions\NotFoundException');
