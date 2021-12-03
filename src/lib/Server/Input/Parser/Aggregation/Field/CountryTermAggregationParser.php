<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Aggregation\Field;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation\AbstractTermAggregation;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation\Field\CountryTermAggregation;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Server\Input\Parser\Aggregation\AbstractTermAggregationParser;

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

class_alias(CountryTermAggregationParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\Field\CountryTermAggregationParser');
