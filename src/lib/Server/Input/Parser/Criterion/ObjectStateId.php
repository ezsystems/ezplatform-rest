<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Rest\Input\BaseParser;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Contracts\Rest\Exceptions;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\ObjectStateId as ObjectStateIdCriterion;

/**
 * Parser for ObjectStateId Criterion.
 */
class ObjectStateId extends BaseParser
{
    /**
     * Parses input structure to a ObjectStateId Criterion object.
     *
     * @param array $data
     * @param \EzSystems\EzPlatformRest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \EzSystems\EzPlatformRest\Exceptions\Parser
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Query\Criterion\ObjectStateId
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('ObjectStateIdCriterion', $data)) {
            throw new Exceptions\Parser('Invalid <ObjectStateIdCriterion> format');
        }

        return new ObjectStateIdCriterion(explode(',', $data['ObjectStateIdCriterion']));
    }
}

class_alias(ObjectStateId::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\ObjectStateId');
