<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Rest\Input\BaseParser;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Exceptions;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\LocationRemoteId as LocationRemoteIdCriterion;

/**
 * Parser for LocationRemoteId Criterion.
 */
class LocationRemoteId extends BaseParser
{
    /**
     * Parses input structure to a Criterion object.
     *
     * @param array $data
     * @param \EzSystems\EzPlatformRest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \EzSystems\EzPlatformRest\Exceptions\Parser
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Query\Criterion\LocationRemoteId
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('LocationRemoteIdCriterion', $data)) {
            throw new Exceptions\Parser('Invalid <LocationRemoteIdCriterion> format');
        }

        return new LocationRemoteIdCriterion(explode(',', $data['LocationRemoteIdCriterion']));
    }
}

class_alias(LocationRemoteId::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\LocationRemoteId');
