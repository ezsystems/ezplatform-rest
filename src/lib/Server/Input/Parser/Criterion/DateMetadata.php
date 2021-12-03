<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\DateMetadata as DateMetadataCriterion;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\Operator;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;

/**
 * Parser for ViewInput Criterion.
 */
class DateMetadata extends BaseParser
{
    private const OPERATORS = [
        'IN' => Operator::IN,
        'EQ' => Operator::EQ,
        'GT' => Operator::GT,
        'GTE' => Operator::GTE,
        'LT' => Operator::LT,
        'LTE' => Operator::LTE,
        'BETWEEN' => Operator::BETWEEN,
    ];

    /**
     * Parses input structure to a Criterion object.
     *
     * @param string[] $data
     *
     * @throws \Ibexa\Contracts\Rest\Exceptions\Parser
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher): DateMetadataCriterion
    {
        if (!isset($data['DateMetadataCriterion'])) {
            throw new Exceptions\Parser('Invalid <DateMetaDataCriterion> format');
        }

        $dateMetadata = $data['DateMetadataCriterion'];

        if (!isset($dateMetadata['Target'])) {
            throw new Exceptions\Parser('Invalid <Target> format');
        }

        $target = strtolower($dateMetadata['Target']);

        if (!in_array($target, DateMetadataCriterion::TARGETS, true)) {
            throw new Exceptions\Parser('Invalid <Target> format');
        }

        if (!isset($dateMetadata['Value'])) {
            throw new Exceptions\Parser('Invalid <Value> format');
        }

        if (!in_array(gettype($dateMetadata['Value']), ['integer', 'array'], true)) {
            throw new Exceptions\Parser('Invalid <Value> format');
        }

        $value = $dateMetadata['Value'];

        if (!isset($dateMetadata['Operator'])) {
            throw new Exceptions\Parser('Invalid <Operator> format');
        }

        $operator = $this->getOperator($dateMetadata['Operator']);

        return new DateMetadataCriterion($target, $operator, $value);
    }

    /**
     * Get operator for the given literal name.
     *
     * For the full list of supported operators:
     *
     * @see \Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\DateMetadata::OPERATORS
     */
    private function getOperator(string $operatorName): string
    {
        $operatorName = strtoupper($operatorName);
        if (!isset(self::OPERATORS[$operatorName])) {
            throw new Exceptions\Parser(
                sprintf(
                    'Unexpected DateMetadata operator. Expected one of: %s',
                    implode(', ', array_keys(self::OPERATORS))
                )
            );
        }

        return self::OPERATORS[$operatorName];
    }
}

class_alias(DateMetadata::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\DateMetadata');
