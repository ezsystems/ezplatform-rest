<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Aggregation\Field;

use eZ\Publish\API\Repository\Values\Content\Query\Aggregation\AbstractStatsAggregation;
use eZ\Publish\API\Repository\Values\Content\Query\Aggregation\Field\FloatStatsAggregation;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Server\Input\Parser\Aggregation\AbstractStatsAggregationParser;
use Ibexa\Contracts\Rest\Exceptions;

final class FloatStatsAggregationParser extends AbstractStatsAggregationParser
{
    protected function getAggregationName(): string
    {
        return 'FloatStatsAggregation';
    }

    protected function parseAggregation(array $data, ParsingDispatcher $parsingDispatcher): AbstractStatsAggregation
    {
        if (!array_key_exists('contentTypeIdentifier', $data)) {
            throw new Exceptions\Parser("Missing 'contentTypeIdentifier' element for {$this->getAggregationName()}");
        }

        if (!array_key_exists('fieldDefinitionIdentifier', $data)) {
            throw new Exceptions\Parser("Missing 'fieldDefinitionIdentifier' element for {$this->getAggregationName()}.");
        }

        return new FloatStatsAggregation(
            $data['name'],
            $data['contentTypeIdentifier'],
            $data['fieldDefinitionIdentifier']
        );
    }
}

class_alias(FloatStatsAggregationParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\Field\FloatStatsAggregationParser');
