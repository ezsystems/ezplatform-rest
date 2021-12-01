<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Aggregation;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation\AbstractRangeAggregation;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation\RawRangeAggregation;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;

final class RawRangeAggregationParser extends AbstractRangeAggregationParser
{
    protected function getAggregationName(): string
    {
        return 'RawRangeAggregation';
    }

    protected function parseAggregation(array $data, ParsingDispatcher $parsingDispatcher): AbstractRangeAggregation
    {
        return new RawRangeAggregation(
            $data['name'],
            $data['fieldName']
        );
    }
}

class_alias(RawRangeAggregationParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\RawRangeAggregationParser');
