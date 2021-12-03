<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser\FacetBuilder;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\FacetBuilder\DateRangeFacetBuilder;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;

/**
 * Parser for DateRange facet builder.
 */
class DateRangeParser extends BaseParser
{
    /**
     * Parses input structure to a FacetBuilder object.
     *
     * @param array $data
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \Ibexa\Contracts\Rest\Exceptions\Parser
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Query\FacetBuilder\DateRangeFacetBuilder
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        throw new Exceptions\Parser('<DateRange> is not supported yet');
        /* @todo: DateRangeFacetBuilder is an abstract class and has no descendants (?)

        if (!array_key_exists('DateRange', $data)) {
            throw new Exceptions\Parser('Invalid <DateRange> format');
        }

        $selectType = [
            'CREATED' => DateRangeFacetBuilder::CREATED,
            'MODIFIED' => DateRangeFacetBuilder::MODIFIED,
            'PUBLISHED' => DateRangeFacetBuilder::PUBLISHED,
        ];

        if (isset($data['DateRange']['select'])) {
            $type = $data['DateRange']['select'];

            if (!isset($selectType[$type])) {
                throw new Exceptions\Parser('<DateRange> unknown type (supported: '.implode (', ', array_keys($selectType)).')');
            }

            $data['type'] = DateRangeFacetBuilder::$type;

            unset($data['DateRange']['select']);
        }

        return new DateRangeFacetBuilder($data['DateRange']);
        */
    }
}

class_alias(DateRangeParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\FacetBuilder\DateRangeParser');
