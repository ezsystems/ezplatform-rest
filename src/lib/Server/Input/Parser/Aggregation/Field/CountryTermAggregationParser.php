<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\Field;

use eZ\Publish\API\Repository\Values\Content\Query\Aggregation\AbstractTermAggregation;
use eZ\Publish\API\Repository\Values\Content\Query\Aggregation\Field\CountryTermAggregation;
use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\AbstractTermAggregationParser;
use EzSystems\EzPlatformRest\Exceptions;

final class CountryTermAggregationParser extends AbstractTermAggregationParser
{
    protected function getAggregationName(): string
    {
        return 'CountryTermAggregation';
    }

    protected function parseAggregation(array $data, ParsingDispatcher $parsingDispatcher): AbstractTermAggregation
    {
        if (!array_key_exists('contentTypeIdentifier', $data)) {
            throw new Exceptions\Parser("Missing 'contentTypeIdentifier' element for {$this->getAggregationName()}");
        }

        if (!array_key_exists('fieldDefinitionIdentifier', $data)) {
            throw new Exceptions\Parser("Missing 'fieldDefinitionIdentifier' element for {$this->getAggregationName()}.");
        }

        if (array_key_exists('type', $data)) {
            $this->assertTypeValue($data['type']);
        }

        return new CountryTermAggregation(
            $data['name'],
            $data['contentTypeIdentifier'],
            $data['fieldDefinitionIdentifier'],
            $data['type'] ?? CountryTermAggregation::TYPE_ALPHA_3
        );
    }

    private function assertTypeValue(string $type): void
    {
        $allowedValues = [
            CountryTermAggregation::TYPE_NAME,
            CountryTermAggregation::TYPE_IDC,
            CountryTermAggregation::TYPE_ALPHA_2,
            CountryTermAggregation::TYPE_ALPHA_3,
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
