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
 * ObjectStateGroupList value object visitor.
 */
class ObjectStateGroupList extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Server\Values\ObjectStateGroupList $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('ObjectStateGroupList');
        $visitor->setHeader('Content-Type', $generator->getMediaType('ObjectStateGroupList'));
        //@todo Needs refactoring, disabling certain headers should not be done this way
        $visitor->setHeader('Accept-Patch', false);

        $generator->startAttribute('href', $this->router->generate('ezpublish_rest_loadObjectStateGroups'));
        $generator->endAttribute('href');

        $generator->startList('ObjectStateGroup');
        foreach ($data->groups as $group) {
            $visitor->visitValueObject($group);
        }
        $generator->endList('ObjectStateGroup');

        $generator->endObjectElement('ObjectStateGroupList');
    }
}

class_alias(ObjectStateGroupList::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\ObjectStateGroupList');
