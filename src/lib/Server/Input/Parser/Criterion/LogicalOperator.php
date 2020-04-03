<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Input\Parser\Criterion;

use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\Server\Input\Parser\Criterion;

/**
 * Parser for LogicalOperator Criterion.
 */
abstract class LogicalOperator extends Criterion
{
    abstract public function parse(array $data, ParsingDispatcher $parsingDispatcher);

    /**
     * @param array $criteriaByType
     *
     * @return array
     */
    protected function getFlattenedCriteriaData(array $criteriaByType)
    {
        $criteria = [];
        foreach ($criteriaByType as $type => $criterion) {
            if (!is_array($criterion) || !$this->isZeroBasedArray($criterion)) {
                $criterion = [$criterion];
            }

            foreach ($criterion as $criterionElement) {
                $criteria[] = [
                    'type' => $type,
                    'data' => $criterionElement,
                ];
            }
        }

        return $criteria;
    }

    /**
     * Checks if the given $value is zero based.
     *
     * @param array $value
     *
     * @return bool
     */
    protected function isZeroBasedArray(array $value)
    {
        reset($value);

        return empty($value) || key($value) === 0;
    }
}
