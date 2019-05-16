<?php

/**
 * File containing the ObjectStateGroupList ValueObjectVisitor class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\Visitor;

/**
 * ObjectStateGroupList value object visitor.
 */
class ObjectStateGroupList extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \EzSystems\EzPlatformRest\Output\Visitor $visitor
     * @param \EzSystems\EzPlatformRest\Output\Generator $generator
     * @param \EzSystems\EzPlatformRest\Server\Values\ObjectStateGroupList $data
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
