<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser;

use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Exceptions;
use Ibexa\Rest\Server\Values\RestViewInput;
use Ibexa\Rest\Input\BaseParser;

/**
 * Parser for ViewInput.
 */
class ViewInput extends BaseParser
{
    /**
     * Parses input structure to a RestViewInput struct.
     *
     * @param array $data
     * @param \EzSystems\EzPlatformRest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \EzSystems\EzPlatformRest\Exceptions\Parser
     *
     * @return \EzSystems\EzPlatformRest\Server\Values\RestViewInput
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $restViewInput = new RestViewInput();

        // identifier
        if (!array_key_exists('identifier', $data)) {
            throw new Exceptions\Parser('Missing <identifier> attribute for <ViewInput>.');
        }
        $restViewInput->identifier = $data['identifier'];

        // language params
        $restViewInput->languageCode = $data['languageCode'] ?? null;
        $restViewInput->useAlwaysAvailable = $data['useAlwaysAvailable'] ?? null;

        // query
        if (!array_key_exists('Query', $data) || !is_array($data['Query'])) {
            throw new Exceptions\Parser('Missing <Query> attribute for <ViewInput>.');
        }

        $restViewInput->query = $parsingDispatcher->parse($data['Query'], 'application/vnd.ez.api.internal.ContentQuery');

        return $restViewInput;
    }
}

class_alias(ViewInput::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\ViewInput');
