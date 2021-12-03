<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\Visitor;
use Ibexa\Rest\Server\Values\RestFieldDefinition as ValuesRestFieldDefinition;

/**
 * FieldDefinitionList value object visitor.
 */
class FieldDefinitionList extends RestContentTypeBase
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Server\Values\FieldDefinitionList $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $fieldDefinitionList = $data;
        $contentType = $fieldDefinitionList->contentType;

        $urlTypeSuffix = $this->getUrlTypeSuffix($contentType->status);

        $generator->startObjectElement('FieldDefinitions', 'FieldDefinitionList');
        $visitor->setHeader('Content-Type', $generator->getMediaType('FieldDefinitionList'));
        //@todo Needs refactoring, disabling certain headers should not be done this way
        $visitor->setHeader('Accept-Patch', false);

        $generator->startAttribute(
            'href',
            $this->router->generate(
                'ezpublish_rest_loadContentType' . $urlTypeSuffix . 'FieldDefinitionList',
                [
                    'contentTypeId' => $contentType->id,
                ]
            )
        );
        $generator->endAttribute('href');

        $generator->startList('FieldDefinition');
        foreach ($fieldDefinitionList->fieldDefinitions as $fieldDefinition) {
            $visitor->visitValueObject(
                new ValuesRestFieldDefinition($contentType, $fieldDefinition)
            );
        }
        $generator->endList('FieldDefinition');

        $generator->endObjectElement('FieldDefinitions');
    }
}

class_alias(FieldDefinitionList::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\FieldDefinitionList');
