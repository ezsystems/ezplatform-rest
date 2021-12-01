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
 * LocationList value object visitor.
 */
class LocationList extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Server\Values\LocationList $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('LocationList');
        $visitor->setHeader('Content-Type', $generator->getMediaType('LocationList'));

        $generator->startAttribute('href', $data->path);
        $generator->endAttribute('href');

        $generator->startList('Location');

        foreach ($data->locations as $restLocation) {
            $generator->startObjectElement('Location');
            $generator->startAttribute(
                'href',
                $this->router->generate(
                    'ezpublish_rest_loadLocation',
                    ['locationPath' => trim($restLocation->location->pathString, '/')]
                )
            );
            $generator->endAttribute('href');
            $generator->endObjectElement('Location');
        }

        $generator->endList('Location');

        $generator->endObjectElement('LocationList');
    }
}

class_alias(LocationList::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\LocationList');
