<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Output\ValueObjectVisitor;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;

/**
 * ContentObjectStates value object visitor.
 */
class ContentObjectStates extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Values\ContentObjectStates $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('ContentObjectStates');
        $visitor->setHeader('Content-Type', $generator->getMediaType('ContentObjectStates'));
        $visitor->setHeader('Accept-Patch', $generator->getMediaType('ContentObjectStates'));

        $generator->startList('ObjectState');

        foreach ($data->states as $state) {
            $generator->startObjectElement('ObjectState');
            $generator->startAttribute(
                'href',
                $this->router->generate(
                    'ezpublish_rest_loadObjectState',
                    [
                        'objectStateGroupId' => $state->groupId,
                        'objectStateId' => $state->objectState->id,
                    ]
                )
            );
            $generator->endAttribute('href');
            $generator->endObjectElement('ObjectState');
        }

        $generator->endList('ObjectState');

        $generator->endObjectElement('ContentObjectStates');
    }
}

class_alias(ContentObjectStates::class, 'EzSystems\EzPlatformRest\Output\ValueObjectVisitor\ContentObjectStates');
