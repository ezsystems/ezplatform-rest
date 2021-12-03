<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType as APIContentType;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\Visitor;
use Ibexa\Rest\Output\FieldTypeSerializer;

/**
 * RestFieldDefinition value object visitor.
 *
 * @todo $fieldSettings & $validatorConfiguration (missing from spec)
 */
class RestFieldDefinition extends RestContentTypeBase
{
    /**
     * @var \Ibexa\Rest\Output\FieldTypeSerializer
     */
    protected $fieldTypeSerializer;

    /**
     * @param \Ibexa\Rest\Output\FieldTypeSerializer $fieldTypeSerializer
     */
    public function __construct(FieldTypeSerializer $fieldTypeSerializer)
    {
        $this->fieldTypeSerializer = $fieldTypeSerializer;
    }

    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Server\Values\RestFieldDefinition $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $restFieldDefinition = $data;
        $fieldDefinition = $restFieldDefinition->fieldDefinition;
        $contentType = $restFieldDefinition->contentType;

        $urlTypeSuffix = $this->getUrlTypeSuffix($contentType->status);

        $generator->startObjectElement('FieldDefinition');
        $visitor->setHeader('Content-Type', $generator->getMediaType('FieldDefinition'));

        if ($contentType->status === APIContentType::STATUS_DRAFT) {
            $visitor->setHeader('Accept-Patch', $generator->getMediaType('FieldDefinitionUpdate'));
        }

        $generator->startAttribute(
            'href',
            $this->router->generate(
                "ezpublish_rest_loadContentType{$urlTypeSuffix}FieldDefinition",
                [
                    'contentTypeId' => $contentType->id,
                    'fieldDefinitionId' => $fieldDefinition->id,
                ]
            )
        );
        $generator->endAttribute('href');

        $generator->startValueElement('id', $fieldDefinition->id);
        $generator->endValueElement('id');

        $generator->startValueElement('identifier', $fieldDefinition->identifier);
        $generator->endValueElement('identifier');

        $generator->startValueElement('fieldType', $fieldDefinition->fieldTypeIdentifier);
        $generator->endValueElement('fieldType');

        $generator->startValueElement('fieldGroup', $fieldDefinition->fieldGroup);
        $generator->endValueElement('fieldGroup');

        $generator->startValueElement('position', $fieldDefinition->position);
        $generator->endValueElement('position');

        $generator->startValueElement(
            'isTranslatable',
            $this->serializeBool($generator, $fieldDefinition->isTranslatable)
        );
        $generator->endValueElement('isTranslatable');

        $generator->startValueElement(
            'isRequired',
            $this->serializeBool($generator, $fieldDefinition->isRequired)
        );
        $generator->endValueElement('isRequired');

        $generator->startValueElement(
            'isInfoCollector',
            $this->serializeBool($generator, $fieldDefinition->isInfoCollector)
        );
        $generator->endValueElement('isInfoCollector');

        $this->fieldTypeSerializer->serializeFieldDefaultValue(
            $generator,
            $fieldDefinition->fieldTypeIdentifier,
            $fieldDefinition->defaultValue
        );

        $generator->startValueElement(
            'isSearchable',
            $this->serializeBool($generator, $fieldDefinition->isSearchable)
        );
        $generator->endValueElement('isSearchable');

        $this->visitNamesList($generator, $fieldDefinition->getNames());

        $descriptions = $fieldDefinition->getDescriptions();
        if (is_array($descriptions)) {
            $this->visitDescriptionsList($generator, $descriptions);
        }

        $this->fieldTypeSerializer->serializeFieldSettings(
            $generator,
            $fieldDefinition->fieldTypeIdentifier,
            $fieldDefinition->getFieldSettings()
        );

        $this->fieldTypeSerializer->serializeValidatorConfiguration(
            $generator,
            $fieldDefinition->fieldTypeIdentifier,
            $fieldDefinition->getValidatorConfiguration()
        );

        $generator->endObjectElement('FieldDefinition');
    }
}

class_alias(RestFieldDefinition::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\RestFieldDefinition');
