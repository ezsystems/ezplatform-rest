<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Rest\Input\BaseParser;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Exceptions;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\LanguageCode as LanguageCodeCriterion;

/**
 * Parser for LanguageCode Criterion.
 */
class LanguageCode extends BaseParser
{
    /**
     * Parses input structure to a LanguageCode Criterion object.
     *
     * @param array $data
     * @param \EzSystems\EzPlatformRest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \EzSystems\EzPlatformRest\Exceptions\Parser
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Query\Criterion\LanguageCode
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('LanguageCodeCriterion', $data)) {
            throw new Exceptions\Parser('Invalid <LanguageCodeCriterion> format');
        }

        return new LanguageCodeCriterion(explode(',', $data['LanguageCodeCriterion']));
    }
}

class_alias(LanguageCode::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\LanguageCode');
