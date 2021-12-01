<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\LogicalNot as LogicalNotCriterion;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Server\Input\Parser\Criterion as CriterionParser;

/**
 * Parser for LogicalNot Criterion.
 */
class LogicalNot extends CriterionParser
{
    /**
     * Parses input structure to a Criterion object.
     *
     * @param array $data
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \Ibexa\Contracts\Rest\Exceptions\Parser
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\LogicalNot
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('NOT', $data) && !is_array($data['NOT'])) {
            throw new Exceptions\Parser('Invalid <NOT> format');
        }

        if (count($data['NOT']) > 1) {
            throw new Exceptions\Parser('NOT element can only contain one subitem');
        }
        $criterionName = key($data['NOT']);
        $criterionData = current($data['NOT']);
        $criteria = $this->dispatchCriterion($criterionName, $criterionData, $parsingDispatcher);

        return new LogicalNotCriterion($criteria);
    }
}

class_alias(LogicalNot::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\LogicalNot');
