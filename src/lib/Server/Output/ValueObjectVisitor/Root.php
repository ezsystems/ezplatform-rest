<?php

/**
 * File containing the Root ValueObjectVisitor class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\Visitor;

/**
 * Root value object visitor.
 */
class Root extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \EzSystems\EzPlatformRest\Output\Visitor $visitor
     * @param \EzSystems\EzPlatformRest\Output\Generator $generator
     * @param \EzSystems\EzPlatformRest\Values\Root $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('Root');
        $visitor->setHeader('Content-Type', $generator->getMediaType('Root'));

        foreach ($data->getResources() as $resource) {
            if ($resource->mediaType === '') {
                $generator->startHashElement($resource->name);
                $generator->startAttribute('media-type', $resource->mediaType);
                $generator->endAttribute('media-type');
            } else {
                $generator->startObjectElement($resource->name, $resource->mediaType);
            }

            $generator->startAttribute('href', $resource->href);
            $generator->endAttribute('href');

            if ($resource->mediaType === '') {
                $generator->endHashElement($resource->name);
            } else {
                $generator->endObjectElement($resource->name);
            }
        }

        $generator->endObjectElement('Root');
    }
}
