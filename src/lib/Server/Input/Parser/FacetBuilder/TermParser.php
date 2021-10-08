<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser\FacetBuilder;

use Ibexa\Rest\Input\BaseParser;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Exceptions;
use eZ\Publish\API\Repository\Values\Content\Query\FacetBuilder\TermFacetBuilder;

/**
 * Parser for Term facet builder.
 */
class TermParser extends BaseParser
{
    /**
     * Parses input structure to a FacetBuilder object.
     *
     * @param array $data
     * @param \EzSystems\EzPlatformRest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \EzSystems\EzPlatformRest\Exceptions\Parser
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Query\FacetBuilder\TermFacetBuilder
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('Term', $data)) {
            throw new Exceptions\Parser('Invalid <Term> format');
        }

        return new TermFacetBuilder($data['Term']);
    }
}

class_alias(TermParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\FacetBuilder\TermParser');
