<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\ValueObject;

/**
 * SessionInput view model.
 */
class SessionInput extends ValueObject
{
    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $password;
}

class_alias(SessionInput::class, 'EzSystems\EzPlatformRest\Server\Values\SessionInput');
