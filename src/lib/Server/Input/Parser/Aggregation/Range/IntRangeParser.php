<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Aggregation\Range;

use Ibexa\Contracts\Rest\Input\ParsingDispatcher;

final class IntRangeParser extends AbstractRangeParser
{
    protected function visitRangeValue(ParsingDispatcher $parsingDispatcher, $value): ?int
    {
        if ($value === null) {
            return null;
        }

        return (int)$value;
    }
}

class_alias(IntRangeParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\Range\IntRangeParser');
