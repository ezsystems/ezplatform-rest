<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Rest\Input\BaseParser;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Contracts\Rest\Exceptions;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\ParentLocationId as ParentLocationIdCriterion;

/**
 * Parser for LocationId Criterion.
 */
class ParentLocationId extends BaseParser
{
    /**
     * Parses input structure to a ParentLocationId Criterion object.
     *
     * @param array $data
     * @param \EzSystems\EzPlatformRest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \EzSystems\EzPlatformRest\Exceptions\Parser
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Query\Criterion\ParentLocationId
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('ParentLocationIdCriterion', $data)) {
            throw new Exceptions\Parser('Invalid <ParentLocationIdCriterion> format');
        }

        return new ParentLocationIdCriterion(explode(',', $data['ParentLocationIdCriterion']));
    }
}

class_alias(ParentLocationId::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\ParentLocationId');
