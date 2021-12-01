<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;

/**
 * SeeOther Value object visitor.
 */
class SeeOther extends ValueObjectVisitor
{
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $visitor->setStatus(303);
        $visitor->setHeader('Location', $data->redirectUri);
    }
}

class_alias(SeeOther::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\SeeOther');
