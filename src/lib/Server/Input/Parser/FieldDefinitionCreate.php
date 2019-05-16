<?php

/**
 * File containing the FieldDefinitionCreate parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Input\Parser;

use EzSystems\EzPlatformRest\Input\BaseParser;
use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\Input\FieldTypeParser;
use EzSystems\EzPlatformRest\Input\ParserTools;
use eZ\Publish\API\Repository\ContentTypeService;
use EzSystems\EzPlatformRest\Exceptions;
use Exception;

/**
 * Parser for FieldDefinitionCreate.
 */
class FieldDefinitionCreate extends BaseParser
{
    /**
     * ContentType service.
     *
     * @var \eZ\Publish\API\Repository\ContentTypeService
     */
    protected $contentTypeService;

    /**
     * FieldType parser.
     *
     * @var \EzSystems\EzPlatformRest\Input\FieldTypeParser
     */
    protected $fieldTypeParser;

    /**
     * Parser tools.
     *
     * @var \EzSystems\EzPlatformRest\Input\ParserTools
     */
    protected $parserTools;

    /**
     * Construct.
     *
     * @param \eZ\Publish\API\Repository\ContentTypeService $contentTypeService
     * @param \EzSystems\EzPlatformRest\Input\FieldTypeParser $fieldTypeParser
     * @param \EzSystems\EzPlatformRest\Input\ParserTools $parserTools
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
     * @param \EzSystems\EzPlatformRest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \EzSystems\EzPlatformRest\Exceptions\Parser If an error is found while parsing
     *
     * @return \eZ\Publish\API\Repository\Values\ContentType\FieldDefinitionCreateStruct
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('identifier', $data)) {
            throw new Exceptions\Parser("Missing 'identifier' element for FieldDefinitionCreate.");
        }

        if (!array_key_exists('fieldType', $data)) {
            throw new Exceptions\Parser("Missing 'fieldType' element for FieldDefinitionCreate.");
        }

        $fieldDefinitionCreate = $this->contentTypeService->newFieldDefinitionCreateStruct(
            $data['identifier'],
            $data['fieldType']
        );

        // @todo XSD says that descriptions is mandatory, but content type can be created without it
        if (array_key_exists('names', $data)) {
            if (!is_array($data['names']) || !array_key_exists('value', $data['names']) || !is_array($data['names']['value'])) {
                throw new Exceptions\Parser("Invalid 'names' element for FieldDefinitionCreate.");
            }

            $fieldDefinitionCreate->names = $this->parserTools->parseTranslatableList($data['names']);
        }

        // @todo XSD says that descriptions is mandatory, but content type can be created without it
        if (array_key_exists('descriptions', $data)) {
            if (!is_array($data['descriptions']) || !array_key_exists('value', $data['descriptions']) || !is_array($data['descriptions']['value'])) {
                throw new Exceptions\Parser("Invalid 'descriptions' element for FieldDefinitionCreate.");
            }

            $fieldDefinitionCreate->descriptions = $this->parserTools->parseTranslatableList($data['descriptions']);
        }

        // @todo XSD says that fieldGroup is mandatory, but content type can be created without it
        if (array_key_exists('fieldGroup', $data)) {
            $fieldDefinitionCreate->fieldGroup = $data['fieldGroup'];
        }

        // @todo XSD says that position is mandatory, but content type can be created without it
        if (array_key_exists('position', $data)) {
            $fieldDefinitionCreate->position = (int)$data['position'];
        }

        // @todo XSD says that isTranslatable is mandatory, but content type can be created without it
        if (array_key_exists('isTranslatable', $data)) {
            $fieldDefinitionCreate->isTranslatable = $this->parserTools->parseBooleanValue($data['isTranslatable']);
        }

        // @todo XSD says that isRequired is mandatory, but content type can be created without it
        if (array_key_exists('isRequired', $data)) {
            $fieldDefinitionCreate->isRequired = $this->parserTools->parseBooleanValue($data['isRequired']);
        }

        // @todo XSD says that isInfoCollector is mandatory, but content type can be created without it
        if (array_key_exists('isInfoCollector', $data)) {
            $fieldDefinitionCreate->isInfoCollector = $this->parserTools->parseBooleanValue($data['isInfoCollector']);
        }

        // @todo XSD says that isSearchable is mandatory, but content type can be created without it
        if (array_key_exists('isSearchable', $data)) {
            $fieldDefinitionCreate->isSearchable = $this->parserTools->parseBooleanValue($data['isSearchable']);
        }

        // @todo XSD says that defaultValue is mandatory, but content type can be created without it
        if (array_key_exists('defaultValue', $data)) {
            try {
                $fieldDefinitionCreate->defaultValue = $this->fieldTypeParser->parseValue(
                    $data['fieldType'],
                    $data['defaultValue']
                );
            } catch (Exception $e) {
                throw new Exceptions\Parser("Invalid 'defaultValue' element for FieldDefinitionCreate.", 0, $e);
            }
        }

        if (array_key_exists('validatorConfiguration', $data)) {
            $fieldDefinitionCreate->validatorConfiguration = $this->fieldTypeParser->parseValidatorConfiguration(
                $data['fieldType'],
                $data['validatorConfiguration']
            );
        }

        if (array_key_exists('fieldSettings', $data)) {
            $fieldDefinitionCreate->fieldSettings = $this->fieldTypeParser->parseFieldSettings(
                $data['fieldType'],
                $data['fieldSettings']
            );
        }

        return $fieldDefinitionCreate;
    }
}
