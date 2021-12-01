<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\ObjectState\ObjectStateGroup as ObjectStateGroupValue;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;

/**
 * ObjectStateGroup value object visitor.
 */
class ObjectStateGroup extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Contracts\Core\Repository\Values\ObjectState\ObjectStateGroup $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('ObjectStateGroup');
        $visitor->setHeader('Content-Type', $generator->getMediaType('ObjectStateGroup'));
        $visitor->setHeader('Accept-Patch', $generator->getMediaType('ObjectStateGroupUpdate'));
        $this->visitObjectStateGroupAttributes($visitor, $generator, $data);
        $generator->endObjectElement('ObjectStateGroup');
    }

    protected function visitObjectStateGroupAttributes(Visitor $visitor, Generator $generator, ObjectStateGroupValue $data)
    {
        $generator->startAttribute(
            'href',
            $this->router->generate('ezpublish_rest_loadObjectStateGroup', ['objectStateGroupId' => $data->id])
        );
        $generator->endAttribute('href');

        $generator->startValueElement('id', $data->id);
        $generator->endValueElement('id');

        $generator->startValueElement('identifier', $data->identifier);
        $generator->endValueElement('identifier');

        $generator->startValueElement('defaultLanguageCode', $data->defaultLanguageCode);
        $generator->endValueElement('defaultLanguageCode');

        $generator->startValueElement('languageCodes', implode(',', $data->languageCodes));
        $generator->endValueElement('languageCodes');

        $generator->startObjectElement('ObjectStates', 'ObjectStateList');
        $generator->startAttribute(
            'href',
            $this->router->generate('ezpublish_rest_loadObjectStates', ['objectStateGroupId' => $data->id])
        );
        $generator->endAttribute('href');
        $generator->endObjectElement('ObjectStates');

        $this->visitNamesList($generator, $data->getNames());
        $this->visitDescriptionsList($generator, $data->getDescriptions());
    }
}

class_alias(ObjectStateGroup::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\ObjectStateGroup');
