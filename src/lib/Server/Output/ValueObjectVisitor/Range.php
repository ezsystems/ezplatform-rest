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

final class Range extends ValueObjectVisitor
{
    /**
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation\Range $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data): void
    {
        $generator->startObjectElement('Range');
        $generator->valueElement('from', $data->getFrom());
        $generator->valueElement('to', $data->getTo());
        $generator->endObjectElement('Range');
    }
}

class_alias(Range::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\Range');
