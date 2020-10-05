<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Output\DelegateValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Visitor;
use EzSystems\EzPlatformRest\Values\RestObjectState;

final class ObjectState extends ValueObjectVisitor implements DelegateValueObjectVisitor
{
    /**
     * @param \eZ\Publish\API\Repository\Values\ObjectState\ObjectState $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data): void
    {
        $visitor->visitValueObject(
            new RestObjectState(
                $data,
                $data->getObjectStateGroup()->id
            )
        );
    }
}
