<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Aggregation;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation\AbstractTermAggregation;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;

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
            $aggregation->setMinCount((int)$aggregationData['minCount']);
        }

        return $aggregation;
    }

    abstract protected function getAggregationName(): string;

    abstract protected function parseAggregation(
        array $data,
        ParsingDispatcher $parsingDispatcher
    ): AbstractTermAggregation;
}

class_alias(AbstractTermAggregationParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\AbstractTermAggregationParser');
