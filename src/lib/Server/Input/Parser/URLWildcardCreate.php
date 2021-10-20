<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser;

use Ibexa\Rest\Input\BaseParser;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\ParserTools;
use Ibexa\Contracts\Rest\Exceptions;

/**
 * Parser for URLWildcardCreate.
 */
class URLWildcardCreate extends BaseParser
{
    /**
     * Parser tools.
     *
     * @var \EzSystems\EzPlatformRest\Input\ParserTools
     */
    protected $parserTools;

    /**
     * Construct.
     *
     * @param \EzSystems\EzPlatformRest\Input\ParserTools $parserTools
     */
    public function __construct(ParserTools $parserTools)
    {
        $this->parserTools = $parserTools;
    }

    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \EzSystems\EzPlatformRest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return array
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('sourceUrl', $data)) {
            throw new Exceptions\Parser("Missing 'sourceUrl' value for URLWildcardCreate.");
        }

        if (!array_key_exists('destinationUrl', $data)) {
            throw new Exceptions\Parser("Missing 'destinationUrl' value for URLWildcardCreate.");
        }

        if (!array_key_exists('forward', $data)) {
            throw new Exceptions\Parser("Missing 'forward' value for URLWildcardCreate.");
        }

        $data['forward'] = $this->parserTools->parseBooleanValue($data['forward']);

        return $data;
    }
}

class_alias(URLWildcardCreate::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\URLWildcardCreate');
