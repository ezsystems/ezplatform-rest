<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion as CriterionValue;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Server\Input\Parser\Criterion as CriterionParser;

/**
 * Content/Location Query Parser.
 */
abstract class Query extends CriterionParser
{
    /**
     * Parses input structure to a Query.
     *
     * @param array $data
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \Ibexa\Contracts\Rest\Exceptions\Parser
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Query
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $query = $this->buildQuery();

        if (array_key_exists('Filter', $data) && is_array($data['Filter'])) {
            $query->filter = $this->processCriteriaArray($data['Filter'], $parsingDispatcher);
        }

        if (array_key_exists('Query', $data) && is_array($data['Query'])) {
            $query->query = $this->processCriteriaArray($data['Query'], $parsingDispatcher);
        }

        // limit
        if (array_key_exists('limit', $data)) {
            $query->limit = (int)$data['limit'];
        }

        // offset
        if (array_key_exists('offset', $data)) {
            $query->offset = (int)$data['offset'];
        }

        // SortClauses
        // -- [SortClauseName: direction|data]
        if (array_key_exists('SortClauses', $data)) {
            $query->sortClauses = $this->processSortClauses($data['SortClauses'], $parsingDispatcher);
        }

        // FacetBuilders
        // -- facetBuilderListType
        if (array_key_exists('FacetBuilders', $data)) {
            $facetBuilders = [];
            foreach ($data['FacetBuilders'] as $facetBuilderName => $facetBuilderData) {
                $facetBuilders[] = $this->dispatchFacetBuilder($facetBuilderName, $facetBuilderData, $parsingDispatcher);
            }
            $query->facetBuilders = $facetBuilders;
        }

        if (array_key_exists('Aggregations', $data)) {
            foreach ($data['Aggregations'] as $aggregation) {
                $aggregationName = array_key_first($aggregation);
                $aggregationData = $aggregation[$aggregationName];

                $query->aggregations[] = $this->dispatchAggregation(
                    $aggregationName,
                    $aggregationData,
                    $parsingDispatcher
                );
            }
        }

        return $query;
    }

    /**
     * Builds and returns the Query (Location or Content object).
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Query
     */
    abstract protected function buildQuery();

    /**
     * @param array $criteriaArray
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion|null A criterion, or a LogicalAnd with a set of Criterion, or null if an empty array was given
     */
    private function processCriteriaArray(array $criteriaArray, ParsingDispatcher $parsingDispatcher)
    {
        if (count($criteriaArray) === 0) {
            return null;
        }

        $criteria = [];
        foreach ($criteriaArray as $criterionName => $criterionData) {
            $criteria[] = $this->dispatchCriterion($criterionName, $criterionData, $parsingDispatcher);
        }

        return (count($criteria) === 1) ? $criteria[0] : new CriterionValue\LogicalAnd($criteria);
    }

    /**
     * Handles SortClause data.
     *
     * @param array $sortClausesArray
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return array
     */
    private function processSortClauses(array $sortClausesArray, ParsingDispatcher $parsingDispatcher)
    {
        $sortClauses = [];
        foreach ($sortClausesArray as $sortClauseName => $sortClauseData) {
            if (!is_array($sortClauseData) || !isset($sortClauseData[0])) {
                $sortClauseData = [$sortClauseData];
            }

            foreach ($sortClauseData as $data) {
                $sortClauses[] = $this->dispatchSortClause($sortClauseName, $data, $parsingDispatcher);
            }
        }

        return $sortClauses;
    }
}

class_alias(Query::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Query');
