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
use Ibexa\Rest\Output\DelegateValueObjectVisitor;
use Ibexa\Rest\Server\Values\RestContentType;

final class ContentType extends ValueObjectVisitor implements DelegateValueObjectVisitor
{
    /**
     * @param \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data): void
    {
        $visitor->visitValueObject(
            new RestContentType(
                $data,
                $data->getFieldDefinitions()->toArray()
            )
        );
    }
}

class_alias(ContentType::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\ContentType');
