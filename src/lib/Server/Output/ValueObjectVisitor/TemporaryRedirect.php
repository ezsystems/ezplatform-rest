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
 * TemporaryRedirect value object visitor.
 */
class TemporaryRedirect extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Server\Values\TemporaryRedirect $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $visitor->setStatus(307);
        $visitor->setHeader('Location', $data->redirectUri);
    }
}

class_alias(TemporaryRedirect::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\TemporaryRedirect');
