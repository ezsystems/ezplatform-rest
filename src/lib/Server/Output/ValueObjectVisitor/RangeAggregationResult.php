<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use eZ\Publish\API\Repository\Values\Content\Search\AggregationResult\RangeAggregationResultEntry;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Visitor;

final class RangeAggregationResult extends ValueObjectVisitor
{
    /**
     * @param \eZ\Publish\API\Repository\Values\Content\Search\AggregationResult\RangeAggregationResult $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data): void
    {
        $generator->startObjectElement('RangeAggregationResult');

        $visitor->setHeader('Content-Type', $generator->getMediaType('RangeAggregationResult'));

        $generator->valueElement('name', $data->getName());

        $generator->startList('entries');
        foreach ($data->getEntries() as $entry) {
            $this->visitEntry($visitor, $generator, $entry);
        }
        $generator->endList('entries');

        $generator->endObjectElement('RangeAggregationResult');
    }

    private function visitEntry(Visitor $visitor, Generator $generator, RangeAggregationResultEntry $entry): void
    {
        $generator->startObjectElement('RangeAggregationResultEntry');
        $this->visitKey($visitor, $generator, $entry->getKey());
        $this->visitCount($visitor, $generator, $entry->getCount());
        $generator->endObjectElement('RangeAggregationResultEntry');
    }

    /**
     * @param \eZ\Publish\API\Repository\Values\Content\Query\Aggregation\Range $key
     */
    private function visitKey(Visitor $visitor, Generator $generator, $key): void
    {
        if (is_object($key)) {
            $generator->startHashElement('key');
            $visitor->visitValueObject($key);
            $generator->endHashElement('key');
        } else {
            $generator->valueElement('key', $key);
        }
    }

    private function visitCount(Visitor $visitor, Generator $generator, int $count): void
    {
        $generator->valueElement('count', $count);
    }
}
