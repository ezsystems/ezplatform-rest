<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\Range;

use DateTimeImmutable;
use DateTimeInterface;
use EzSystems\EzPlatformRest\Input\ParsingDispatcher;

final class DateTimeRangeParser extends AbstractRangeParser
{
    protected function visitRangeValue(ParsingDispatcher $parsingDispatcher, $value): ?DateTimeInterface
    {
        if ($value === null) {
            return null;
        }

        return new DateTimeImmutable($value);
    }
}
