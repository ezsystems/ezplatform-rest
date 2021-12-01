<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Aggregation;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation\AbstractTermAggregation;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation\RawTermAggregation;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;

final class RawTermAggregationParser extends AbstractTermAggregationParser
{
    protected function getAggregationName(): string
    {
        return 'RawTermAggregation';
    }

    protected function parseAggregation(array $data, ParsingDispatcher $parsingDispatcher): AbstractTermAggregation
    {
        return new RawTermAggregation(
            $data['name'],
            $data['fieldName'],
        );
    }
}

class_alias(RawTermAggregationParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\RawTermAggregationParser');
