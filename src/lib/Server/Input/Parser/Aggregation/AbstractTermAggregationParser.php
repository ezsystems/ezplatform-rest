<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation;

use eZ\Publish\API\Repository\Values\Content\Query\Aggregation\AbstractTermAggregation;
use EzSystems\EzPlatformRest\Exceptions;
use EzSystems\EzPlatformRest\Input\BaseParser;
use EzSystems\EzPlatformRest\Input\ParsingDispatcher;

abstract class AbstractTermAggregationParser extends BaseParser
{
    final public function parse(array $data, ParsingDispatcher $parsingDispatcher): AbstractTermAggregation
    {
        if (!array_key_exists($this->getAggregationName(), $data)) {
            throw new Exceptions\Parser("Invalid <{$this->getAggregationName()}> format");
        }

        $aggregationData = $data[$this->getAggregationName()];

        if (!array_key_exists('name', $aggregationData)) {
            throw new Exceptions\Parser("Missing 'name' element for {$this->getAggregationName()}.");
        }

        $aggregation = $this->parseAggregation($aggregationData, $parsingDispatcher);

        if (array_key_exists('limit', $aggregationData)) {
            $aggregation->setLimit((int)$aggregationData['limit']);
        }

        if (array_key_exists('minCount', $aggregationData)) {
            $aggregation->setLimit((int)$aggregationData['minCount']);
        }

        return $aggregation;
    }

    abstract protected function getAggregationName(): string;

    abstract protected function parseAggregation(
        array $data,
        ParsingDispatcher $parsingDispatcher
    ): AbstractTermAggregation;
}
