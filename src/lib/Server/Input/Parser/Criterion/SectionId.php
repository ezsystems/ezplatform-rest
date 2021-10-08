<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Rest\Input\BaseParser;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Exceptions;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\SectionId as SectionIdCriterion;

/**
 * Parser for SectionId Criterion.
 */
class SectionId extends BaseParser
{
    /**
     * Parses input structure to a SectionId Criterion object.
     *
     * @param array $data
     * @param \EzSystems\EzPlatformRest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \EzSystems\EzPlatformRest\Exceptions\Parser
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Query\Criterion\SectionId
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('SectionIdCriterion', $data)) {
            throw new Exceptions\Parser('Invalid <SectionIdCriterion> format');
        }

        return new SectionIdCriterion($data['SectionIdCriterion']);
    }
}

class_alias(SectionId::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\SectionId');
