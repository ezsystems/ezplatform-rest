<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\ValueObject;

/**
 * Struct representing a freshly created User.
 */
class CreatedUser extends ValueObject
{
    /**
     * The created user.
     *
     * @var \Ibexa\Rest\Server\Values\RestUser
     */
    public $user;
}

class_alias(CreatedUser::class, 'EzSystems\EzPlatformRest\Server\Values\CreatedUser');
