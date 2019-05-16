<?php

/**
 * File containing the VersionUpdate parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Input\Parser;

use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\Input\FieldTypeParser;
use EzSystems\EzPlatformRest\Exceptions;
use eZ\Publish\API\Repository\ContentService;
use EzSystems\EzPlatformRest\Input\BaseParser;

/**
 * Parser for VersionUpdate.
 */
class VersionUpdate extends BaseParser
{
    /**
     * Content service.
     *
     * @var \eZ\Publish\API\Repository\ContentService
     */
    protected $contentService;

    /**
     * FieldType parser.
     *
     * @var \EzSystems\EzPlatformRest\Input\FieldTypeParser
     */
    protected $fieldTypeParser;

    /**
     * Construct from content service.
     *
     * @param \eZ\Publish\API\Repository\ContentService $contentService
     * @param \EzSystems\EzPlatformRest\Input\FieldTypeParser $fieldTypeParser
     */
    public function __construct(ContentService $contentService, FieldTypeParser $fieldTypeParser)
    {
        $this->contentService = $contentService;
        $this->fieldTypeParser = $fieldTypeParser;
    }

    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \EzSystems\EzPlatformRest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \eZ\Publish\API\Repository\Values\Content\ContentUpdateStruct
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $contentUpdateStruct = $this->contentService->newContentUpdateStruct();

        // Missing initial language code

        if (array_key_exists('initialLanguageCode', $data)) {
            $contentUpdateStruct->initialLanguageCode = $data['initialLanguageCode'];
        }

        // @todo Where to set the user?
        // @todo Where to set modification date?

        if (array_key_exists('fields', $data)) {
            if (!is_array($data['fields']) || !array_key_exists('field', $data['fields']) || !is_array($data['fields']['field'])) {
                throw new Exceptions\Parser("Invalid 'fields' element for VersionUpdate.");
            }

            $contentId = $this->requestParser->parseHref($data['__url'], 'contentId');

            foreach ($data['fields']['field'] as $fieldData) {
                if (!array_key_exists('fieldDefinitionIdentifier', $fieldData)) {
                    throw new Exceptions\Parser("Missing 'fieldDefinitionIdentifier' element in field data for VersionUpdate.");
                }

                if (!array_key_exists('fieldValue', $fieldData)) {
                    throw new Exceptions\Parser("Missing 'fieldValue' element for '{$fieldData['fieldDefinitionIdentifier']}' identifier in VersionUpdate.");
                }

                $fieldValue = $this->fieldTypeParser->parseFieldValue(
                    $contentId,
                    $fieldData['fieldDefinitionIdentifier'],
                    $fieldData['fieldValue']
                );

                $languageCode = null;
                if (array_key_exists('languageCode', $fieldData)) {
                    $languageCode = $fieldData['languageCode'];
                }

                $contentUpdateStruct->setField($fieldData['fieldDefinitionIdentifier'], $fieldValue, $languageCode);
            }
        }

        return $contentUpdateStruct;
    }
}
