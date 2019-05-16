<?php

/**
 * File containing the UserCreate parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Input\Parser;

use EzSystems\EzPlatformRest\Input\BaseParser;
use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\Input\FieldTypeParser;
use EzSystems\EzPlatformRest\Input\ParserTools;
use EzSystems\EzPlatformRest\Exceptions;
use eZ\Publish\API\Repository\UserService;
use eZ\Publish\API\Repository\ContentTypeService;

/**
 * Parser for UserCreate.
 */
class UserCreate extends BaseParser
{
    /**
     * User service.
     *
     * @var \eZ\Publish\API\Repository\UserService
     */
    protected $userService;

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
     * @param \eZ\Publish\API\Repository\UserService $userService
     * @param \eZ\Publish\API\Repository\ContentTypeService $contentTypeService
     * @param \EzSystems\EzPlatformRest\Input\FieldTypeParser $fieldTypeParser
     * @param \EzSystems\EzPlatformRest\Input\ParserTools $parserTools
     */
    public function __construct(UserService $userService, ContentTypeService $contentTypeService, FieldTypeParser $fieldTypeParser, ParserTools $parserTools)
    {
        $this->userService = $userService;
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
     * @return \eZ\Publish\API\Repository\Values\User\UserCreateStruct
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $contentType = null;
        if (array_key_exists('ContentType', $data) && is_array($data['ContentType'])) {
            if (!array_key_exists('_href', $data['ContentType'])) {
                throw new Exceptions\Parser("Missing '_href' attribute for ContentType element in UserCreate.");
            }

            $contentType = $this->contentTypeService->loadContentType(
                $this->requestParser->parseHref($data['ContentType']['_href'], 'contentTypeId')
            );
        }

        if (!array_key_exists('mainLanguageCode', $data)) {
            throw new Exceptions\Parser("Missing 'mainLanguageCode' element for UserCreate.");
        }

        if (!array_key_exists('login', $data)) {
            throw new Exceptions\Parser("Missing 'login' element for UserCreate.");
        }

        if (!array_key_exists('email', $data)) {
            throw new Exceptions\Parser("Missing 'email' element for UserCreate.");
        }

        if (!array_key_exists('password', $data)) {
            throw new Exceptions\Parser("Missing 'password' element for UserCreate.");
        }

        $userCreateStruct = $this->userService->newUserCreateStruct(
            $data['login'],
            $data['email'],
            $data['password'],
            $data['mainLanguageCode'],
            $contentType
        );

        if (array_key_exists('Section', $data) && is_array($data['Section'])) {
            if (!array_key_exists('_href', $data['Section'])) {
                throw new Exceptions\Parser("Missing '_href' attribute for Section element in UserCreate.");
            }

            $userCreateStruct->sectionId = $this->requestParser->parseHref($data['Section']['_href'], 'sectionId');
        }

        if (array_key_exists('remoteId', $data)) {
            $userCreateStruct->remoteId = $data['remoteId'];
        }

        if (array_key_exists('enabled', $data)) {
            $userCreateStruct->enabled = $this->parserTools->parseBooleanValue($data['enabled']);
        }

        if (!array_key_exists('fields', $data) || !is_array($data['fields']) || !is_array($data['fields']['field'])) {
            throw new Exceptions\Parser("Missing or invalid 'fields' element for UserCreate.");
        }

        foreach ($data['fields']['field'] as $fieldData) {
            if (!array_key_exists('fieldDefinitionIdentifier', $fieldData)) {
                throw new Exceptions\Parser("Missing 'fieldDefinitionIdentifier' element in field data for UserCreate.");
            }

            $fieldDefinition = $userCreateStruct->contentType->getFieldDefinition($fieldData['fieldDefinitionIdentifier']);
            if (!$fieldDefinition) {
                throw new Exceptions\Parser(
                    "'{$fieldData['fieldDefinitionIdentifier']}' is invalid field definition identifier for '{$userCreateStruct->contentType->identifier}' content type in UserCreate."
                );
            }

            if (!array_key_exists('fieldValue', $fieldData)) {
                throw new Exceptions\Parser("Missing 'fieldValue' element for '{$fieldData['fieldDefinitionIdentifier']}' identifier in UserCreate.");
            }

            $fieldValue = $this->fieldTypeParser->parseValue($fieldDefinition->fieldTypeIdentifier, $fieldData['fieldValue']);

            $languageCode = null;
            if (array_key_exists('languageCode', $fieldData)) {
                $languageCode = $fieldData['languageCode'];
            }

            $userCreateStruct->setField($fieldData['fieldDefinitionIdentifier'], $fieldValue, $languageCode);
        }

        return $userCreateStruct;
    }
}
