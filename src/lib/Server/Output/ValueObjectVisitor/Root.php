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
 * Root value object visitor.
 */
class Root extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Values\Root $data
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

class_alias(Root::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\Root');
