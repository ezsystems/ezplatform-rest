<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser;

use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;
use Ibexa\Rest\Server\Values\SessionInput as SessionInputValue;

/**
 * Parser for SessionInput.
 */
class SessionInput extends BaseParser
{
    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \Ibexa\Rest\Server\Values\SessionInput
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $sessionInput = new SessionInputValue();

        if (!array_key_exists('login', $data)) {
            throw new Exceptions\Parser("Missing 'login' attribute for SessionInput.");
        }

        $sessionInput->login = $data['login'];

        if (!array_key_exists('password', $data)) {
            throw new Exceptions\Parser("Missing 'password' attribute for SessionInput.");
        }

        $sessionInput->password = $data['password'];

        return $sessionInput;
    }
}

class_alias(SessionInput::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\SessionInput');
