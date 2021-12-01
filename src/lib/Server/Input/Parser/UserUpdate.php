<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\ContentService;
use Ibexa\Contracts\Core\Repository\UserService;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;
use Ibexa\Rest\Input\FieldTypeParser;
use Ibexa\Rest\Input\ParserTools;
use Ibexa\Rest\Server\Values\RestUserUpdateStruct;

/**
 * Parser for UserUpdate.
 */
class UserUpdate extends BaseParser
{
    /**
     * User service.
     *
     * @var \Ibexa\Contracts\Core\Repository\UserService
     */
    protected $userService;

    /**
     * Content service.
     *
     * @var \Ibexa\Contracts\Core\Repository\ContentService
     */
    protected $contentService;

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
     * @param \Ibexa\Contracts\Core\Repository\UserService $userService
     * @param \Ibexa\Contracts\Core\Repository\ContentService $contentService
     * @param \Ibexa\Rest\Input\FieldTypeParser $fieldTypeParser
     * @param \Ibexa\Rest\Input\ParserTools $parserTools
     */
    public function __construct(UserService $userService, ContentService $contentService, FieldTypeParser $fieldTypeParser, ParserTools $parserTools)
    {
        $this->userService = $userService;
        $this->contentService = $contentService;
        $this->fieldTypeParser = $fieldTypeParser;
        $this->parserTools = $parserTools;
    }

    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \Ibexa\Rest\Server\Values\RestUserUpdateStruct
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $parsedData = [];

        //@todo XSD has a login element, but it's not possible to update login

        if (array_key_exists('email', $data)) {
            $parsedData['email'] = $data['email'];
        }

        if (array_key_exists('password', $data)) {
            $parsedData['password'] = $data['password'];
        }

        if (array_key_exists('enabled', $data)) {
            $parsedData['enabled'] = $this->parserTools->parseBooleanValue($data['enabled']);
        }

        if (array_key_exists('mainLanguageCode', $data)) {
            $parsedData['mainLanguageCode'] = $data['mainLanguageCode'];
        }

        if (array_key_exists('Section', $data) && is_array($data['Section'])) {
            if (!array_key_exists('_href', $data['Section'])) {
                throw new Exceptions\Parser("Missing '_href' attribute for the Section element in UserUpdate.");
            }

            $parsedData['sectionId'] = $this->requestParser->parseHref($data['Section']['_href'], 'sectionId');
        }

        if (array_key_exists('remoteId', $data)) {
            $parsedData['remoteId'] = $data['remoteId'];
        }

        if (array_key_exists('fields', $data)) {
            $userId = $this->requestParser->parseHref($data['__url'], 'userId');

            if (!is_array($data['fields']) || !array_key_exists('field', $data['fields']) || !is_array($data['fields']['field'])) {
                throw new Exceptions\Parser("Invalid 'fields' element for UserUpdate.");
            }

            $parsedData['fields'] = [];
            foreach ($data['fields']['field'] as $fieldData) {
                if (!array_key_exists('fieldDefinitionIdentifier', $fieldData)) {
                    throw new Exceptions\Parser("Missing 'fieldDefinitionIdentifier' element in field data for UserUpdate.");
                }

                if (!array_key_exists('fieldValue', $fieldData)) {
                    throw new Exceptions\Parser("Missing 'fieldValue' element for the '{$fieldData['fieldDefinitionIdentifier']}' identifier in UserUpdate.");
                }

                $fieldValue = $this->fieldTypeParser->parseFieldValue($userId, $fieldData['fieldDefinitionIdentifier'], $fieldData['fieldValue']);

                $languageCode = null;
                if (array_key_exists('languageCode', $fieldData)) {
                    $languageCode = $fieldData['languageCode'];
                }

                $parsedData['fields'][$fieldData['fieldDefinitionIdentifier']] = [
                    'fieldValue' => $fieldValue,
                    'languageCode' => $languageCode,
                ];
            }
        }

        $userUpdateStruct = $this->userService->newUserUpdateStruct();

        if (!empty($parsedData)) {
            if (array_key_exists('email', $parsedData)) {
                $userUpdateStruct->email = $parsedData['email'];
            }

            if (array_key_exists('password', $parsedData)) {
                $userUpdateStruct->password = $parsedData['password'];
            }

            if (array_key_exists('enabled', $parsedData)) {
                $userUpdateStruct->enabled = $parsedData['enabled'];
            }

            if (array_key_exists('mainLanguageCode', $parsedData) || array_key_exists('remoteId', $parsedData)) {
                $userUpdateStruct->contentMetadataUpdateStruct = $this->contentService->newContentMetadataUpdateStruct();

                if (array_key_exists('mainLanguageCode', $parsedData)) {
                    $userUpdateStruct->contentMetadataUpdateStruct->mainLanguageCode = $parsedData['mainLanguageCode'];
                }

                if (array_key_exists('remoteId', $parsedData)) {
                    $userUpdateStruct->contentMetadataUpdateStruct->remoteId = $parsedData['remoteId'];
                }
            }

            if (array_key_exists('fields', $parsedData)) {
                $userUpdateStruct->contentUpdateStruct = $this->contentService->newContentUpdateStruct();

                foreach ($parsedData['fields'] as $fieldDefinitionIdentifier => $fieldValue) {
                    $userUpdateStruct->contentUpdateStruct->setField(
                        $fieldDefinitionIdentifier,
                        $fieldValue['fieldValue'],
                        $fieldValue['languageCode']
                    );
                }
            }
        }

        return new RestUserUpdateStruct(
            $userUpdateStruct,
            array_key_exists('sectionId', $parsedData) ? $parsedData['sectionId'] : null
        );
    }
}

class_alias(UserUpdate::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\UserUpdate');
