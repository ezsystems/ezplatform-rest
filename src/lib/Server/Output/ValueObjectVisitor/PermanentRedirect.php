<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\Visitor;

/**
 * PermanentRedirect value object visitor.
 */
class PermanentRedirect extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \EzSystems\EzPlatformRest\Output\Visitor $visitor
     * @param \EzSystems\EzPlatformRest\Output\Generator $generator
     * @param \EzSystems\EzPlatformRest\Server\Values\PermanentRedirect $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $visitor->setStatus(301);
        $visitor->setHeader('Location', $data->redirectUri);
    }
}

class_alias(PermanentRedirect::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\PermanentRedirect');
