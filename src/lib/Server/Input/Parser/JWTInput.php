<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser;

use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;
use Ibexa\Rest\Server\Values\JWTInput as JWTInputValue;

final class JWTInput extends BaseParser
{
    public function parse(array $data, ParsingDispatcher $parsingDispatcher): JWTInputValue
    {
        if (!\array_key_exists('username', $data)) {
            throw new Exceptions\Parser("Missing 'username' attribute for JWTInput.");
        }

        if (!\array_key_exists('password', $data)) {
            throw new Exceptions\Parser("Missing 'password' attribute for JWTInput.");
        }

        return new JWTInputValue($data['username'], $data['password']);
    }
}

class_alias(JWTInput::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\JWTInput');
