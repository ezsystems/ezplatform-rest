<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\Location;

use eZ\Publish\API\Repository\Values\Content\Query\Aggregation\AbstractTermAggregation;
use eZ\Publish\API\Repository\Values\Content\Query\Aggregation\Location\SubtreeTermAggregation;
use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\AbstractTermAggregationParser;
use EzSystems\EzPlatformRest\Exceptions;

final class SubtreeTermAggregationParser extends AbstractTermAggregationParser
{
    protected function getAggregationName(): string
    {
        return 'SubtreeTermAggregation';
    }

    protected function parseAggregation(array $data, ParsingDispatcher $parsingDispatcher): AbstractTermAggregation
    {
        if (!array_key_exists('pathString', $data)) {
            throw new Exceptions\Parser("Missing 'pathString' element for SubtreeTerm.");
        }

        return new SubtreeTermAggregation(
            $data['name'],
            $data['pathString']
        );
    }
}
