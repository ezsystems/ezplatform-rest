<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Aggregation;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation\AbstractStatsAggregation;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation\RawStatsAggregation;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;

final class RawStatsAggregationParser extends AbstractStatsAggregationParser
{
    protected function getAggregationName(): string
    {
        return 'RawStatsAggregation';
    }

    protected function parseAggregation(array $data, ParsingDispatcher $parsingDispatcher): AbstractStatsAggregation
    {
        return new RawStatsAggregation(
            $data['name'],
            $data['fieldName']
        );
    }
}

class_alias(RawStatsAggregationParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\RawStatsAggregationParser');
