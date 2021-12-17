<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;
use Ibexa\Rest\Values\RestObjectState as RestObjectStateValue;

/**
 * ObjectStateList value object visitor.
 */
class ObjectStateList extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Server\Values\ObjectStateList $data
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

class_alias(ObjectStateList::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\ObjectStateList');
