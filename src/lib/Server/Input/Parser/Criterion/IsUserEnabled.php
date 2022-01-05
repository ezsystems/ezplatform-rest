<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\IsUserEnabled as IsUserEnabledCriterion;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;
use Ibexa\Rest\Input\ParserTools;

class IsUserEnabled extends BaseParser
{
    /** @var \Ibexa\Rest\Input\ParserTools */
    protected $parserTools;

    public function __construct(ParserTools $parserTools)
    {
        $this->parserTools = $parserTools;
    }

    public function parse(array $data, ParsingDispatcher $parsingDispatcher): IsUserEnabledCriterion
    {
        if (!array_key_exists('IsUserEnabledCriterion', $data)) {
            throw new Exceptions\Parser('Invalid <IsUserEnabledCriterion> format');
        }

        return new IsUserEnabledCriterion($this->parserTools->parseBooleanValue($data['IsUserEnabledCriterion']));
    }
}

class_alias(IsUserEnabled::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\IsUserEnabled');
