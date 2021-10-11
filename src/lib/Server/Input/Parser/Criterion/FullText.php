<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Rest\Input\BaseParser;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Contracts\Rest\Exceptions;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\FullText as FullTextCriterion;

/**
 * Parser for FullText Criterion.
 */
class FullText extends BaseParser
{
    /**
     * Parses input structure to a FullText criterion.
     *
     * @param array $data
     * @param \EzSystems\EzPlatformRest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \EzSystems\EzPlatformRest\Exceptions\Parser
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Query\Criterion\FullText
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
