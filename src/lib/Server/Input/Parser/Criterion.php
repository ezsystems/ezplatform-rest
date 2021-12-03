<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;

/**
 * Parser for ViewInput.
 */
abstract class Criterion extends BaseParser
{
    /**
     * @var array
     */
    protected static $criterionIdMap = [
        'AND' => 'LogicalAnd',
        'OR' => 'LogicalOr',
        'NOT' => 'LogicalNot',
    ];

    /**
     * Dispatches parsing of a criterion name + data to its own parser.
     *
     * @param string $criterionName
     * @param mixed $criterionData
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \Ibexa\Contracts\Rest\Exceptions\Parser
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion
     */
    public function dispatchCriterion($criterionName, $criterionData, ParsingDispatcher $parsingDispatcher)
    {
        $mediaType = $this->getCriterionMediaType($criterionName);
        try {
            return $parsingDispatcher->parse([$criterionName => $criterionData], $mediaType);
        } catch (Exceptions\Parser $e) {
            throw new Exceptions\Parser("Invalid Criterion id <$criterionName> in <AND>", 0, $e);
        }
    }

    /**
     * Dispatches parsing of a facet builder name + data to its own parser.
     *
     * @param string $facetBuilderName
     * @param mixed $facetBuilderData
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \Ibexa\Contracts\Rest\Exceptions\Parser
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\ValueObject
     */
    public function dispatchFacetBuilder($facetBuilderName, $facetBuilderData, ParsingDispatcher $parsingDispatcher)
    {
        $mediaType = $this->getFacetBuilderMediaType($facetBuilderName);

        try {
            return $parsingDispatcher->parse([$facetBuilderName => $facetBuilderData], $mediaType);
        } catch (Exceptions\Parser $e) {
            throw new Exceptions\Parser("Invalid FacetBuilder id <${facetBuilderName}>", 0, $e);
        }
    }

    /**
     * Dispatches parsing of a aggregation name + data to its own parser.
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation
     */
    public function dispatchAggregation(
        string $aggregationName,
        array $aggregationData,
        ParsingDispatcher $parsingDispatcher
    ): Aggregation {
        return $parsingDispatcher->parse(
            [
                $aggregationName => $aggregationData,
            ],
            $this->getAggregationMediaType($aggregationName)
        );
    }

    /**
     * Dispatches parsing of a sort clause name + direction to its own parser.
     *
     * @param string $sortClauseName
     * @param string $direction
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \Ibexa\Contracts\Rest\Exceptions\Parser
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion
     */
    public function dispatchSortClause($sortClauseName, $direction, ParsingDispatcher $parsingDispatcher)
    {
        $mediaType = $this->getSortClauseMediaType($sortClauseName);

        return $parsingDispatcher->parse([$sortClauseName => $direction], $mediaType);
    }

    protected function getCriterionMediaType($criterionName)
    {
        $criterionName = str_replace('Criterion', '', $criterionName);
        if (isset(self::$criterionIdMap[$criterionName])) {
            $criterionName = self::$criterionIdMap[$criterionName];
        }

        return 'application/vnd.ez.api.internal.criterion.' . $criterionName;
    }

    protected function getSortClauseMediaType($sortClauseName)
    {
        return 'application/vnd.ez.api.internal.sortclause.' . $sortClauseName;
    }

    protected function getFacetBuilderMediaType($facetBuilderName)
    {
        return 'application/vnd.ez.api.internal.facetbuilder.' . $facetBuilderName;
    }

    protected function getAggregationMediaType(string $aggregationName): string
    {
        return 'application/vnd.ez.api.internal.aggregation.' . $aggregationName;
    }
}

class_alias(Criterion::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion');
