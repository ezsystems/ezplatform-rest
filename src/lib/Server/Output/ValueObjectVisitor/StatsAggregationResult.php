<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;

final class StatsAggregationResult extends ValueObjectVisitor
{
    /**
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Search\AggregationResult\StatsAggregationResult $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data): void
    {
        $generator->startObjectElement('StatsAggregationResult');

        $visitor->setHeader('Content-Type', $generator->getMediaType('StatsAggregationResult'));

        $generator->valueElement('name', $data->getName());
        $generator->valueElement('sum', $data->getSum());
        $generator->valueElement('count', $data->getCount());
        $generator->valueElement('min', $data->getMin());
        $generator->valueElement('max', $data->getMax());
        $generator->valueElement('avg', $data->getAvg());

        $generator->endObjectElement('StatsAggregationResult');
    }
}

class_alias(StatsAggregationResult::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\StatsAggregationResult');
