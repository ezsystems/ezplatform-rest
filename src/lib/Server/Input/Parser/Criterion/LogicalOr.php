<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Contracts\Core\Repository\Values;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;

/**
 * Parser for LogicalOr Criterion.
 */
class LogicalOr extends LogicalOperator
{
    /**
     * @var string
     */
    public const TAG_NAME = 'OR';

    /**
     * Parses input structure to a LogicalOr Criterion object.
     *
     * @param array $data
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \Ibexa\Contracts\Rest\Exceptions\Parser
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\LogicalOr
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists(static::TAG_NAME, $data) || !is_array($data[static::TAG_NAME])) {
            throw new Exceptions\Parser('Invalid <' . static::TAG_NAME . '> format');
        }

        $criteria = [];

        $flattenedCriteriaElements = $this->getFlattenedCriteriaData($data[static::TAG_NAME]);
        foreach ($flattenedCriteriaElements as $criterionElement) {
            $criteria[] = $this->dispatchCriterion(
                $criterionElement['type'],
                $criterionElement['data'],
                $parsingDispatcher
            );
        }

        return new Values\Content\Query\Criterion\LogicalOr($criteria);
    }
}

class_alias(LogicalOr::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\LogicalOr');
