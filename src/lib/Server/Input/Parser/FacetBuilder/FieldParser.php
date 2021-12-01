<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser\FacetBuilder;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\FacetBuilder\FieldFacetBuilder;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;

/**
 * Parser for Field facet builder.
 */
class FieldParser extends BaseParser
{
    /**
     * Parses input structure to a FacetBuilder object.
     *
     * @param array $data
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \Ibexa\Contracts\Rest\Exceptions\Parser
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Query\FacetBuilder\FieldFacetBuilder
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('Field', $data)) {
            throw new Exceptions\Parser('Invalid <Field> format');
        }

        $sortType = [
            'COUNT_ASC' => FieldFacetBuilder::COUNT_ASC,
            'COUNT_DESC' => FieldFacetBuilder::COUNT_DESC,
            'TERM_ASC' => FieldFacetBuilder::TERM_ASC,
            'TERM_DESC' => FieldFacetBuilder::TERM_DESC,
        ];

        if (isset($data['Field']['sort'])) {
            $type = $data['Field']['sort'];

            if (!in_array($type, $sortType)) {
                throw new Exceptions\Parser('<Field> unknown sort type (supported: ' . implode(', ', array_keys($sortType)) . ')');
            }

            $data['Field']['sort'] = $sortType[$type];
        } else {
            throw new Exceptions\Parser('<Field> sort type missing (supported: ' . implode(', ', array_keys($sortType)) . ')');
        }

        return new FieldFacetBuilder($data['Field']);
    }
}

class_alias(FieldParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\FacetBuilder\FieldParser');
