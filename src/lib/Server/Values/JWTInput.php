<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Values;

use EzSystems\EzPlatformRest\Value as RestValue;

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
