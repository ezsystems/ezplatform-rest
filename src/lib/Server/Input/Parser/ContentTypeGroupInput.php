<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser;

use DateTime;
use Ibexa\Contracts\Core\Repository\ContentTypeService;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;
use Ibexa\Rest\Input\ParserTools;

/**
 * Parser for ContentTypeGroupInput.
 */
class ContentTypeGroupInput extends BaseParser
{
    /**
     * ContentType service.
     *
     * @var \Ibexa\Contracts\Core\Repository\ContentTypeService
     */
    protected $contentTypeService;

    /**
     * @var \Ibexa\Rest\Input\ParserTools
     */
    protected $parserTools;

    /**
     * Construct.
     *
     * @param \Ibexa\Contracts\Core\Repository\ContentTypeService $contentTypeService
     * @param \Ibexa\Rest\Input\ParserTools $parserTools
     */
    public function __construct(ContentTypeService $contentTypeService, ParserTools $parserTools)
    {
        $this->contentTypeService = $contentTypeService;
        $this->parserTools = $parserTools;
    }

    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentTypeGroupCreateStruct
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        // Since ContentTypeGroupInput is used both for creating and updating ContentTypeGroup and identifier is not
        // required when updating ContentTypeGroup, we need to rely on PAPI to throw the exception on missing
        // identifier when creating a ContentTypeGroup
        // @todo Bring in line with XSD which says that identifier is required always

        $contentTypeGroupIdentifier = null;
        if (array_key_exists('identifier', $data)) {
            $contentTypeGroupIdentifier = $data['identifier'];
        }

        $contentTypeGroupCreateStruct = $this->contentTypeService->newContentTypeGroupCreateStruct($contentTypeGroupIdentifier);

        if (array_key_exists('modificationDate', $data)) {
            $contentTypeGroupCreateStruct->creationDate = new DateTime($data['modificationDate']);
        }

        // @todo mainLanguageCode, names, descriptions?

        if (array_key_exists('User', $data) && is_array($data['User'])) {
            if (!array_key_exists('_href', $data['User'])) {
                throw new Exceptions\Parser("Missing '_href' attribute for the User element in ContentTypeGroupInput.");
            }

            $contentTypeGroupCreateStruct->creatorId = $this->requestParser->parseHref($data['User']['_href'], 'userId');
        }

        return $contentTypeGroupCreateStruct;
    }
}

class_alias(ContentTypeGroupInput::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\ContentTypeGroupInput');
