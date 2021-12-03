<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Aggregation;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation\AbstractRangeAggregation;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;

abstract class AbstractRangeAggregationParser extends BaseParser
{
    final public function parse(array $data, ParsingDispatcher $parsingDispatcher): AbstractRangeAggregation
    {
        if (!array_key_exists($this->getAggregationName(), $data)) {
            throw new Exceptions\Parser("Invalid <{$this->getAggregationName()}> format");
        }

        if (!array_key_exists('ranges', $data[$this->getAggregationName()])) {
            throw new Exceptions\Parser("Missing 'ranges' element for {$this->getAggregationName()}");
        }

        if (!is_array($data[$this->getAggregationName()]['ranges'])) {
            throw new Exceptions\Parser("Invalid 'ranges' element for {$this->getAggregationName()}");
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
    ): AbstractRangeAggregation;

    /**
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation\Range[]
     */
    protected function dispatchRanges(ParsingDispatcher $dispatcher, array $data, string $mediaType): array
    {
        $ranges = [];
        foreach ($data as $rangeData) {
            $ranges[] = $dispatcher->parse($rangeData, $mediaType);
        }

        return $ranges;
    }
}

class_alias(AbstractRangeAggregationParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\AbstractRangeAggregationParser');
