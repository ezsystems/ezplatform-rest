<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Aggregation;

use eZ\Publish\API\Repository\Values\Content\Query\Aggregation\AbstractTermAggregation;
use eZ\Publish\API\Repository\Values\Content\Query\Aggregation\ObjectStateTermAggregation;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Contracts\Rest\Exceptions;

final class ObjectStateTermAggregationParser extends AbstractTermAggregationParser
{
    protected function getAggregationName(): string
    {
        return 'ObjectStateTermAggregation';
    }

    protected function parseAggregation(array $data, ParsingDispatcher $parsingDispatcher): AbstractTermAggregation
    {
        if (!array_key_exists('objectStateGroupIdentifier', $data)) {
            throw new Exceptions\Parser("Missing 'objectStateGroupIdentifier' element for ObjectStateTerm.");
        }

        return new ObjectStateTermAggregation(
            $data['name'],
            $data['objectStateGroupIdentifier']
        );
    }
}

class_alias(ObjectStateTermAggregationParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\ObjectStateTermAggregationParser');
