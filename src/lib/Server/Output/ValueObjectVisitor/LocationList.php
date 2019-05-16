<?php

/**
 * File containing the LocationList ValueObjectVisitor class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\Visitor;

/**
 * LocationList value object visitor.
 */
class LocationList extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \EzSystems\EzPlatformRest\Output\Visitor $visitor
     * @param \EzSystems\EzPlatformRest\Output\Generator $generator
     * @param \EzSystems\EzPlatformRest\Server\Values\LocationList $data
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
                    array('locationPath' => trim($restLocation->location->pathString, '/'))
                )
            );
            $generator->endAttribute('href');
            $generator->endObjectElement('Location');
        }

        $generator->endList('Location');

        $generator->endObjectElement('LocationList');
    }
}
