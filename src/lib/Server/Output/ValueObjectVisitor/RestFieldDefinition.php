<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\Visitor;
use EzSystems\EzPlatformRest\Output\FieldTypeSerializer;
use eZ\Publish\API\Repository\Values\ContentType\ContentType as APIContentType;

/**
 * RestFieldDefinition value object visitor.
 *
 * @todo $fieldSettings & $validatorConfiguration (missing from spec)
 */
class RestFieldDefinition extends RestContentTypeBase
{
    /**
     * @var \EzSystems\EzPlatformRest\Output\FieldTypeSerializer
     */
    protected $fieldTypeSerializer;

    /**
     * @param \EzSystems\EzPlatformRest\Output\FieldTypeSerializer $fieldTypeSerializer
     */
    public function __construct(FieldTypeSerializer $fieldTypeSerializer)
    {
        $this->fieldTypeSerializer = $fieldTypeSerializer;
    }

    /**
     * Visit struct returned by controllers.
     *
     * @param \EzSystems\EzPlatformRest\Output\Visitor $visitor
     * @param \EzSystems\EzPlatformRest\Output\Generator $generator
     * @param \EzSystems\EzPlatformRest\Server\Values\RestFieldDefinition $data
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

        if ($data->path === null) {
            $href = $this->router->generate(
                "ezpublish_rest_loadContentType{$urlTypeSuffix}FieldDefinition",
                [
                    'contentTypeId' => $contentType->id,
                    'fieldDefinitionId' => $fieldDefinition->id,
                ]
            );
        } else {
            $href = $data->path;
        }

        $generator->attribute('href', $href);

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
