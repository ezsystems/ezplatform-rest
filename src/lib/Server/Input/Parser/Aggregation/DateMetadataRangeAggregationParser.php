<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation;

use eZ\Publish\API\Repository\Values\Content\Query\Aggregation\AbstractRangeAggregation;
use eZ\Publish\API\Repository\Values\Content\Query\Aggregation\DateMetadataRangeAggregation;
use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\Exceptions;

final class DateMetadataRangeAggregationParser extends AbstractRangeAggregationParser
{
    protected function getAggregationName(): string
    {
        return 'DateMetadataRangeAggregation';
    }

    protected function parseAggregation(array $data, ParsingDispatcher $parsingDispatcher): AbstractRangeAggregation
    {
        if (!array_key_exists('type', $data)) {
            throw new Exceptions\Parser("Missing 'type' element for DateMetadataRange.");
        }

        $this->assertTypeValue($data['type']);

        return new DateMetadataRangeAggregation(
            $data['name'],
            $data['type'],
            $this->dispatchRanges(
                $parsingDispatcher,
                $data['ranges'],
                'application/vnd.ez.api.internal.aggregation.range.DateTimeRange'
            )
        );
    }

    private function assertTypeValue(string $type): void
    {
        $allowedValues = [
            DateMetadataRangeAggregation::CREATED,
            DateMetadataRangeAggregation::MODIFIED,
            DateMetadataRangeAggregation::PUBLISHED,
        ];

        if (!in_array($type, $allowedValues)) {
            throw new Exceptions\Parser(
                sprintf(
                    "Invalid 'type' value. Expected one of %s, got: %s",
                    implode(', ', $allowedValues),
                    $type
                )
            );
        }
    }
}
