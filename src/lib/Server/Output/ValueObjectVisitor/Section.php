<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\Content\Section as SectionValue;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;

/**
 * Section value object visitor.
 */
class Section extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Section $data
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
            $this->router->generate('ezpublish_rest_loadSection', ['sectionId' => $data->id])
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

class_alias(Section::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\Section');
