<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\ContentTypeService;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;
use Ibexa\Rest\Input\FieldTypeParser;
use Ibexa\Rest\Input\ParserTools;

/**
 * Parser for FieldDefinitionUpdate.
 */
class FieldDefinitionUpdate extends BaseParser
{
    /**
     * ContentType service.
     *
     * @var \Ibexa\Contracts\Core\Repository\ContentTypeService
     */
    protected $contentTypeService;

    /**
     * FieldType parser.
     *
     * @var \Ibexa\Rest\Input\FieldTypeParser
     */
    protected $fieldTypeParser;

    /**
     * Parser tools.
     *
     * @var \Ibexa\Rest\Input\ParserTools
     */
    protected $parserTools;

    /**
     * Construct.
     *
     * @param \Ibexa\Contracts\Core\Repository\ContentTypeService $contentTypeService
     * @param \Ibexa\Rest\Input\ParserTools $parserTools
     */
    public function __construct(ContentTypeService $contentTypeService, FieldTypeParser $fieldTypeParser, ParserTools $parserTools)
    {
        $this->contentTypeService = $contentTypeService;
        $this->fieldTypeParser = $fieldTypeParser;
        $this->parserTools = $parserTools;
    }

    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\ContentType\FieldDefinitionUpdateStruct
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $fieldDefinitionUpdate = $this->contentTypeService->newFieldDefinitionUpdateStruct();

        if (array_key_exists('identifier', $data)) {
            $fieldDefinitionUpdate->identifier = $data['identifier'];
        }

        // @todo XSD says that descriptions is mandatory, but field definition can be updated without it
        if (array_key_exists('names', $data)) {
            if (!is_array($data['names']) || !array_key_exists('value', $data['names']) || !is_array($data['names']['value'])) {
                throw new Exceptions\Parser("Invalid 'names' element for FieldDefinitionUpdate.");
            }

            $fieldDefinitionUpdate->names = $this->parserTools->parseTranslatableList($data['names']);
        }

        // @todo XSD says that descriptions is mandatory, but field definition can be updated without it
        if (array_key_exists('descriptions', $data)) {
            if (!is_array($data['descriptions']) || !array_key_exists('value', $data['descriptions']) || !is_array($data['descriptions']['value'])) {
                throw new Exceptions\Parser("Invalid 'descriptions' element for FieldDefinitionUpdate.");
            }

            $fieldDefinitionUpdate->descriptions = $this->parserTools->parseTranslatableList($data['descriptions']);
        }

        // @todo XSD says that fieldGroup is mandatory, but field definition can be updated without it
        if (array_key_exists('fieldGroup', $data)) {
            $fieldDefinitionUpdate->fieldGroup = $data['fieldGroup'];
        }

        // @todo XSD says that position is mandatory, but field definition can be updated without it
        if (array_key_exists('position', $data)) {
            $fieldDefinitionUpdate->position = (int)$data['position'];
        }

        // @todo XSD says that isTranslatable is mandatory, but field definition can be updated without it
        if (array_key_exists('isTranslatable', $data)) {
            $fieldDefinitionUpdate->isTranslatable = $this->parserTools->parseBooleanValue($data['isTranslatable']);
        }

        // @todo XSD says that isRequired is mandatory, but field definition can be updated without it
        if (array_key_exists('isRequired', $data)) {
            $fieldDefinitionUpdate->isRequired = $this->parserTools->parseBooleanValue($data['isRequired']);
        }

        // @todo XSD says that isInfoCollector is mandatory, but field definition can be updated without it
        if (array_key_exists('isInfoCollector', $data)) {
            $fieldDefinitionUpdate->isInfoCollector = $this->parserTools->parseBooleanValue($data['isInfoCollector']);
        }

        // @todo XSD says that isSearchable is mandatory, but field definition can be updated without it
        if (array_key_exists('isSearchable', $data)) {
            $fieldDefinitionUpdate->isSearchable = $this->parserTools->parseBooleanValue($data['isSearchable']);
        }

        $fieldDefinition = $this->getFieldDefinition($data);

        // @todo XSD says that defaultValue is mandatory, but content type can be created without it
        if (array_key_exists('defaultValue', $data)) {
            $fieldDefinitionUpdate->defaultValue = $this->fieldTypeParser->parseValue(
                $fieldDefinition->fieldTypeIdentifier,
                $data['defaultValue']
            );
        }

        if (array_key_exists('validatorConfiguration', $data)) {
            $fieldDefinitionUpdate->validatorConfiguration = $this->fieldTypeParser->parseValidatorConfiguration(
                $fieldDefinition->fieldTypeIdentifier,
                $data['validatorConfiguration']
            );
        }

        if (array_key_exists('fieldSettings', $data)) {
            $fieldDefinitionUpdate->fieldSettings = $this->fieldTypeParser->parseFieldSettings(
                $fieldDefinition->fieldTypeIdentifier,
                $data['fieldSettings']
            );
        }

        return $fieldDefinitionUpdate;
    }

    /**
     * Returns field definition by 'typeFieldDefinitionDraft' pattern URL.
     *
     * Assumes given $data array has '__url' element set.
     *
     * @todo depends on temporary solution to give parser access to the URL
     *
     * @see \Ibexa\Rest\Server\Controller\ContentType::updateFieldDefinition
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     *
     * @param array $data
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\ContentType\FieldDefinition
     */
    protected function getFieldDefinition(array $data)
    {
        $contentTypeId = $this->requestParser->parseHref($data['__url'], 'contentTypeId');
        $fieldDefinitionId = $this->requestParser->parseHref($data['__url'], 'fieldDefinitionId');

        $contentTypeDraft = $this->contentTypeService->loadContentTypeDraft($contentTypeId);
        foreach ($contentTypeDraft->getFieldDefinitions() as $fieldDefinition) {
            if ($fieldDefinition->id == $fieldDefinitionId) {
                return $fieldDefinition;
            }
        }
        throw new Exceptions\NotFoundException("Field definition not found: '{$data['__url']}'.");
    }
}

class_alias(FieldDefinitionUpdate::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\FieldDefinitionUpdate');
