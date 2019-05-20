<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\Visitor;
use EzSystems\EzPlatformRest\Values\RestObjectState as RestObjectStateValue;

/**
 * ObjectStateList value object visitor.
 */
class ObjectStateList extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \EzSystems\EzPlatformRest\Output\Visitor $visitor
     * @param \EzSystems\EzPlatformRest\Output\Generator $generator
     * @param \EzSystems\EzPlatformRest\Server\Values\ObjectStateList $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('ObjectStateList');
        $visitor->setHeader('Content-Type', $generator->getMediaType('ObjectStateList'));
        //@todo Needs refactoring, disabling certain headers should not be done this way
        $visitor->setHeader('Accept-Patch', false);

        $generator->startAttribute(
            'href',
            $this->router->generate('ezpublish_rest_loadObjectStates', ['objectStateGroupId' => $data->groupId])
        );

        $generator->endAttribute('href');

        $generator->startList('ObjectState');
        foreach ($data->states as $state) {
            $visitor->visitValueObject(
                new RestObjectStateValue($state, $data->groupId)
            );
        }
        $generator->endList('ObjectState');

        $generator->endObjectElement('ObjectStateList');
    }
}
