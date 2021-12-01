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
 * Options value object visitor.
 *
 * @todo coverage add unit test
 */
class Options extends ValueObjectVisitor
{
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $visitor->setHeader('Allow', implode(',', $data->allowedMethods));
        $visitor->setHeader('Content-Length', 0);
        $visitor->setStatus(200);
    }
}

class_alias(Options::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\Options');
