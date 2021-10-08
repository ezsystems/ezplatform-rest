<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Aggregation;

use eZ\Publish\API\Repository\Values\Content\Query\Aggregation\AbstractStatsAggregation;
use Ibexa\Rest\Exceptions;
use Ibexa\Rest\Input\BaseParser;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;

abstract class AbstractStatsAggregationParser extends BaseParser
{
    final public function parse(array $data, ParsingDispatcher $parsingDispatcher): AbstractStatsAggregation
    {
        if (!array_key_exists($this->getAggregationName(), $data)) {
            throw new Exceptions\Parser("Invalid <{$this->getAggregationName()}> format");
        }

        return $this->parseAggregation(
            $data[$this->getAggregationName()],
            $parsingDispatcher
        );
    }

    abstract protected function getAggregationName(): string;

    abstract protected function parseAggregation(
        array $data,
        ParsingDispatcher $parsingDispatcher
    ): AbstractStatsAggregation;
}

class_alias(AbstractStatsAggregationParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\AbstractStatsAggregationParser');
