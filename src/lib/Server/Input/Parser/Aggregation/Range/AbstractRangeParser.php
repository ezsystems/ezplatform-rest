<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Aggregation\Range;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation\Range;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;

abstract class AbstractRangeParser extends BaseParser
{
    final public function parse(array $data, ParsingDispatcher $parsingDispatcher): Range
    {
        if (!array_key_exists('from', $data)) {
            throw new Exceptions\Parser("Missing 'from' element for Range.");
        }

        if (!array_key_exists('to', $data)) {
            throw new Exceptions\Parser("Missing 'from' element for Range.");
        }

        return new Range(
            $this->visitRangeValue($parsingDispatcher, $data['from']),
            $this->visitRangeValue($parsingDispatcher, $data['to'])
        );
    }

    abstract protected function visitRangeValue(ParsingDispatcher $parsingDispatcher, $value);
}

class_alias(AbstractRangeParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\Range\AbstractRangeParser');
