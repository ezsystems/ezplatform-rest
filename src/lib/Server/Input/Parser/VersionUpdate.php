<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\ContentService;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;
use Ibexa\Rest\Input\FieldTypeParser;

/**
 * Parser for VersionUpdate.
 */
class VersionUpdate extends BaseParser
{
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
     * Construct from content service.
     *
     * @param \Ibexa\Contracts\Core\Repository\ContentService $contentService
     * @param \Ibexa\Rest\Input\FieldTypeParser $fieldTypeParser
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
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\ContentUpdateStruct
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
                    throw new Exceptions\Parser("Missing 'fieldDefinitionIdentifier' element in Field data for VersionUpdate.");
                }

                if (!array_key_exists('fieldValue', $fieldData)) {
                    throw new Exceptions\Parser("Missing 'fieldValue' element for the '{$fieldData['fieldDefinitionIdentifier']}' identifier in VersionUpdate.");
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

class_alias(VersionUpdate::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\VersionUpdate');
