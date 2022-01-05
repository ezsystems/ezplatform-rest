<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\IsUserBased as IsUserBasedCriterion;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;
use Ibexa\Rest\Input\ParserTools;

class IsUserBased extends BaseParser
{
    /** @var \Ibexa\Rest\Input\ParserTools */
    protected $parserTools;

    public function __construct(ParserTools $parserTools)
    {
        $this->parserTools = $parserTools;
    }

    public function parse(array $data, ParsingDispatcher $parsingDispatcher): IsUserBasedCriterion
    {
        if (!array_key_exists('IsUserBasedCriterion', $data)) {
            throw new Exceptions\Parser('Invalid <IsUserBasedCriterion> format');
        }

        return new IsUserBasedCriterion($this->parserTools->parseBooleanValue($data['IsUserBasedCriterion']));
    }
}

class_alias(IsUserBased::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\IsUserBased');
