<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Aggregation;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation\AbstractTermAggregation;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation\ContentTypeTermAggregation;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;

final class ContentTypeTermAggregationParser extends AbstractTermAggregationParser
{
    protected function getAggregationName(): string
    {
        return 'ContentTypeTermAggregation';
    }

    protected function parseAggregation(array $data, ParsingDispatcher $parsingDispatcher): AbstractTermAggregation
    {
        return new ContentTypeTermAggregation($data['name']);
    }
}

class_alias(ContentTypeTermAggregationParser::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Aggregation\ContentTypeTermAggregationParser');
