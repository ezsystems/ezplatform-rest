<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser\FacetBuilder;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\FacetBuilder\UserFacetBuilder;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;

/**
 * Parser for User facet builder.
 */
class UserParser extends BaseParser
{
    /**
     * Parses input structure to a FacetBuilder object.
     *
     * @param array $data
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \Ibexa\Contracts\Rest\Exceptions\Parser
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Query\FacetBuilder\UserFacetBuilder
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('User', $data)) {
            throw new Exceptions\Parser('Invalid <User> format');
        }

        $selectType = [
            'OWNER' => UserFacetBuilder::OWNER,
            'GROUP' => UserFacetBuilder::GROUP,
            'MODIFIER' => UserFacetBuilder::MODIFIER,
        ];

        if (isset($data['User']['select'])) {
            $type = $data['User']['select'];

            if (!isset($selectType[$type])) {
                throw new Exceptions\Parser('<User> unknown type (supported: ' . implode(', ', array_keys($selectType)) . ')');
            }

            $data['User']['type'] = $selectType[$type];

            unset($data['User']['select']);
        }

        return new UserFacetBuilder($data['User']);
    }
}

class_alias(UserParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\FacetBuilder\UserParser');
