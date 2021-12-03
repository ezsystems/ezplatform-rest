<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

class JWT extends RestValue
{
    /** @var string */
    public $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}

class_alias(JWT::class, 'EzSystems\EzPlatformRest\Server\Values\JWT');
