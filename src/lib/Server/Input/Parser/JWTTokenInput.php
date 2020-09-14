<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Input\Parser;

use EzSystems\EzPlatformRest\Input\BaseParser;
use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\Exceptions;
use EzSystems\EzPlatformRest\Server\Values\JWTTokenInput as JWTTokenInputValue;

class JWTTokenInput extends BaseParser
{
    public function parse(array $data, ParsingDispatcher $parsingDispatcher): JWTTokenInputValue
    {
        if (!\array_key_exists('username', $data)) {
            throw new Exceptions\Parser("Missing 'username' attribute for JWTTokenInput.");
        }

        if (!array_key_exists('password', $data)) {
            throw new Exceptions\Parser("Missing 'password' attribute for JWTTokenInput.");
        }

        return new JWTTokenInputValue($data['username'], $data['password']);
    }
}
