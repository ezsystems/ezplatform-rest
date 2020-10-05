<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Visitor;

final class StatsAggregationResult extends ValueObjectVisitor
{
    /**
     * @param \eZ\Publish\API\Repository\Values\Content\Search\AggregationResult\StatsAggregationResult $data
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
