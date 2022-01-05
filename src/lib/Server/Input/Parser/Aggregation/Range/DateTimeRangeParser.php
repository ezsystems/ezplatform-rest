<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Aggregation\Range;

use DateTimeImmutable;
use DateTimeInterface;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;

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

class_alias(DateTimeRangeParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\Range\DateTimeRangeParser');
