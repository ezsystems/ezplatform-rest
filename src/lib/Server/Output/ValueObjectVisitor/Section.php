<?php

/**
 * File containing the Section ValueObjectVisitor class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\Visitor;
use eZ\Publish\API\Repository\Values\Content\Section as SectionValue;

/**
 * Section value object visitor.
 */
class Section extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \EzSystems\EzPlatformRest\Output\Visitor $visitor
     * @param \EzSystems\EzPlatformRest\Output\Generator $generator
     * @param \eZ\Publish\API\Repository\Values\Content\Section $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('Section');
        $visitor->setHeader('Content-Type', $generator->getMediaType('Section'));
        $visitor->setHeader('Accept-Patch', $generator->getMediaType('SectionInput'));
        $this->visitSectionAttributes($visitor, $generator, $data);
        $generator->endObjectElement('Section');
    }

    protected function visitSectionAttributes(Visitor $visitor, Generator $generator, SectionValue $data)
    {
        $generator->startAttribute(
            'href',
            $this->router->generate('ezpublish_rest_loadSection', array('sectionId' => $data->id))
        );
        $generator->endAttribute('href');

        $generator->startValueElement('sectionId', $data->id);
        $generator->endValueElement('sectionId');

        $generator->startValueElement('identifier', $data->identifier);
        $generator->endValueElement('identifier');

        $generator->startValueElement('name', $data->name);
        $generator->endValueElement('name');
    }
}
