<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Values;

use EzSystems\EzPlatformRest\Value as RestValue;

class JWTToken extends RestValue
{
    /** @var string */
    public $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}
