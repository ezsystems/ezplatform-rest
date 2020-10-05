<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\Range;

use eZ\Publish\API\Repository\Values\Content\Query\Aggregation\Range;
use EzSystems\EzPlatformRest\Input\BaseParser;
use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\Exceptions;

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
