<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\FullText as FullTextCriterion;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;

/**
 * Parser for FullText Criterion.
 */
class FullText extends BaseParser
{
    /**
     * Parses input structure to a FullText criterion.
     *
     * @param array $data
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \Ibexa\Contracts\Rest\Exceptions\Parser
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\FullText
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('FullTextCriterion', $data)) {
            throw new Exceptions\Parser('Invalid <FullTextCriterion> format');
        }

        return new FullTextCriterion($data['FullTextCriterion']);
    }
}

class_alias(FullText::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\FullText');
