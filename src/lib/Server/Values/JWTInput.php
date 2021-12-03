<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

class JWTInput extends RestValue
{
    /** @var string */
    public $username;

    /** @var string */
    public $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
}

class_alias(JWTInput::class, 'EzSystems\EzPlatformRest\Server\Values\JWTInput');
