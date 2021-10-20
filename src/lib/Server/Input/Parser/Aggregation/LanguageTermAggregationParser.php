<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Aggregation;

use eZ\Publish\API\Repository\Values\Content\Query\Aggregation\AbstractTermAggregation;
use eZ\Publish\API\Repository\Values\Content\Query\Aggregation\LanguageTermAggregation;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;

final class LanguageTermAggregationParser extends AbstractTermAggregationParser
{
    protected function getAggregationName(): string
    {
        return 'LanguageTermAggregation';
    }

    protected function parseAggregation(array $data, ParsingDispatcher $parsingDispatcher): AbstractTermAggregation
    {
        return new LanguageTermAggregation($data['name']);
    }
}

class_alias(LanguageTermAggregationParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\LanguageTermAggregationParser');
