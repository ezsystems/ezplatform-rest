<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentTypeGroup as ContentTypeGroupValue;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;

/**
 * ContentTypeGroup value object visitor.
 */
class ContentTypeGroup extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentTypeGroup $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('ContentTypeGroup');
        $this->visitContentTypeGroupAttributes($visitor, $generator, $data);
        $generator->endObjectElement('ContentTypeGroup');
    }

    protected function visitContentTypeGroupAttributes(Visitor $visitor, Generator $generator, ContentTypeGroupValue $data)
    {
        $visitor->setHeader('Content-Type', $generator->getMediaType('ContentTypeGroup'));
        $visitor->setHeader('Accept-Patch', $generator->getMediaType('ContentTypeGroupInput'));

        $generator->startAttribute(
            'href',
            $this->router->generate(
                'ezpublish_rest_loadContentTypeGroup',
                ['contentTypeGroupId' => $data->id]
            )
        );
        $generator->endAttribute('href');

        $generator->startValueElement('id', $data->id);
        $generator->endValueElement('id');

        $generator->startValueElement('identifier', $data->identifier);
        $generator->endValueElement('identifier');

        $generator->startValueElement('created', $data->creationDate->format('c'));
        $generator->endValueElement('created');

        $generator->startValueElement('modified', $data->modificationDate->format('c'));
        $generator->endValueElement('modified');

        $generator->startObjectElement('Creator', 'User');
        $generator->startAttribute(
            'href',
            $this->router->generate('ezpublish_rest_loadUser', ['userId' => $data->creatorId])
        );
        $generator->endAttribute('href');
        $generator->endObjectElement('Creator');

        $generator->startObjectElement('Modifier', 'User');
        $generator->startAttribute(
            'href',
            $this->router->generate('ezpublish_rest_loadUser', ['userId' => $data->modifierId])
        );
        $generator->endAttribute('href');
        $generator->endObjectElement('Modifier');

        $generator->startObjectElement('ContentTypes', 'ContentTypeInfoList');
        $generator->startAttribute(
            'href',
            $this->router->generate(
                'ezpublish_rest_listContentTypesForGroup',
                ['contentTypeGroupId' => $data->id]
            )
        );
        $generator->endAttribute('href');
        $generator->endObjectElement('ContentTypes');
    }
}

class_alias(ContentTypeGroup::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\ContentTypeGroup');
