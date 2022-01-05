<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\ContentService;
use Ibexa\Contracts\Core\Repository\LocationService;
use Ibexa\Contracts\Core\Repository\UserService;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;
use Ibexa\Rest\Input\FieldTypeParser;
use Ibexa\Rest\Server\Values\RestUserGroupUpdateStruct;

/**
 * Parser for UserGroupUpdate.
 */
class UserGroupUpdate extends BaseParser
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
     * Location service.
     *
     * @var \Ibexa\Contracts\Core\Repository\LocationService
     */
    protected $locationService;

    /**
     * FieldType parser.
     *
     * @var \Ibexa\Rest\Input\FieldTypeParser
     */
    protected $fieldTypeParser;

    /**
     * Construct.
     *
     * @param \Ibexa\Contracts\Core\Repository\UserService $userService
     * @param \Ibexa\Contracts\Core\Repository\ContentService $contentService
     * @param \Ibexa\Contracts\Core\Repository\LocationService $locationService
     * @param \Ibexa\Rest\Input\FieldTypeParser $fieldTypeParser
     */
    public function __construct(UserService $userService, ContentService $contentService, LocationService $locationService, FieldTypeParser $fieldTypeParser)
    {
        $this->userService = $userService;
        $this->contentService = $contentService;
        $this->locationService = $locationService;
        $this->fieldTypeParser = $fieldTypeParser;
    }

    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \Ibexa\Rest\Server\Values\RestUserGroupUpdateStruct
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $parsedData = [];

        if (array_key_exists('mainLanguageCode', $data)) {
            $parsedData['mainLanguageCode'] = $data['mainLanguageCode'];
        }

        if (array_key_exists('Section', $data) && is_array($data['Section'])) {
            if (!array_key_exists('_href', $data['Section'])) {
                throw new Exceptions\Parser("Missing '_href' attribute for the Section element in UserGroupUpdate.");
            }

            $parsedData['sectionId'] = $this->requestParser->parseHref($data['Section']['_href'], 'sectionId');
        }

        if (array_key_exists('remoteId', $data)) {
            $parsedData['remoteId'] = $data['remoteId'];
        }

        if (array_key_exists('fields', $data)) {
            $groupLocationParts = explode('/', $this->requestParser->parseHref($data['__url'], 'groupPath'));

            $groupLocation = $this->locationService->loadLocation(array_pop($groupLocationParts));

            if (!is_array($data['fields']) || !array_key_exists('field', $data['fields']) || !is_array($data['fields']['field'])) {
                throw new Exceptions\Parser("Invalid 'fields' element for UserGroupUpdate.");
            }

            $parsedData['fields'] = [];
            foreach ($data['fields']['field'] as $fieldData) {
                if (!array_key_exists('fieldDefinitionIdentifier', $fieldData)) {
                    throw new Exceptions\Parser("Missing 'fieldDefinitionIdentifier' element in field data for UserGroupUpdate.");
                }

                if (!array_key_exists('fieldValue', $fieldData)) {
                    throw new Exceptions\Parser("Missing 'fieldValue' element for the '{$fieldData['fieldDefinitionIdentifier']}' identifier in UserGroupUpdate.");
                }

                $fieldValue = $this->fieldTypeParser->parseFieldValue($groupLocation->contentId, $fieldData['fieldDefinitionIdentifier'], $fieldData['fieldValue']);

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

        $userGroupUpdateStruct = $this->userService->newUserGroupUpdateStruct();

        if (!empty($parsedData)) {
            if (array_key_exists('mainLanguageCode', $parsedData) || array_key_exists('remoteId', $parsedData)) {
                $userGroupUpdateStruct->contentMetadataUpdateStruct = $this->contentService->newContentMetadataUpdateStruct();

                if (array_key_exists('mainLanguageCode', $parsedData)) {
                    $userGroupUpdateStruct->contentMetadataUpdateStruct->mainLanguageCode = $parsedData['mainLanguageCode'];
                }

                if (array_key_exists('remoteId', $parsedData)) {
                    $userGroupUpdateStruct->contentMetadataUpdateStruct->remoteId = $parsedData['remoteId'];
                }
            }

            if (array_key_exists('fields', $parsedData)) {
                $userGroupUpdateStruct->contentUpdateStruct = $this->contentService->newContentUpdateStruct();

                foreach ($parsedData['fields'] as $fieldDefinitionIdentifier => $fieldValue) {
                    $userGroupUpdateStruct->contentUpdateStruct->setField(
                        $fieldDefinitionIdentifier,
                        $fieldValue['fieldValue'],
                        $fieldValue['languageCode']
                    );
                }
            }
        }

        return new RestUserGroupUpdateStruct(
            $userGroupUpdateStruct,
            array_key_exists('sectionId', $parsedData) ? $parsedData['sectionId'] : null
        );
    }
}

class_alias(UserGroupUpdate::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\UserGroupUpdate');
