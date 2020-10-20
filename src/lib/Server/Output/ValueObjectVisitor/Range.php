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

final class Range extends ValueObjectVisitor
{
    /**
     * @param \eZ\Publish\API\Repository\Values\Content\Query\Aggregation\Range $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data): void
    {
        $generator->startObjectElement('Range');
        $generator->valueElement('from', $data->getFrom());
        $generator->valueElement('to', $data->getTo());
        $generator->endObjectElement('Range');
    }
}
