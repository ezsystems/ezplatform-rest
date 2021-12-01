<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser\FacetBuilder;

use Ibexa\Rest\Input\BaseParser;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\FacetBuilder\SectionFacetBuilder;

/**
 * Parser for Section facet builder.
 */
class SectionParser extends BaseParser
{
    /**
     * Parses input structure to a FacetBuilder object.
     *
     * @param array $data
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \Ibexa\Contracts\Rest\Exceptions\Parser
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Query\FacetBuilder\SectionFacetBuilder
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('Section', $data)) {
            throw new Exceptions\Parser('Invalid <Section> format');
        }

        return new SectionFacetBuilder($data['Section']);
    }
}

class_alias(SectionParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\FacetBuilder\SectionParser');
