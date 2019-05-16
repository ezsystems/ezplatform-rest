<?php

/**
 * File containing the LocationCreate parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Input\Parser;

use EzSystems\EzPlatformRest\Input\BaseParser;
use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\Input\ParserTools;
use EzSystems\EzPlatformRest\Exceptions;
use eZ\Publish\API\Repository\LocationService;

/**
 * Parser for LocationCreate.
 */
class LocationCreate extends BaseParser
{
    /**
     * Location service.
     *
     * @var \eZ\Publish\API\Repository\LocationService
     */
    protected $locationService;

    /**
     * Parser tools.
     *
     * @var \EzSystems\EzPlatformRest\Input\ParserTools
     */
    protected $parserTools;

    /**
     * Construct.
     *
     * @param \eZ\Publish\API\Repository\LocationService $locationService
     * @param \EzSystems\EzPlatformRest\Input\ParserTools $parserTools
     */
    public function __construct(LocationService $locationService, ParserTools $parserTools)
    {
        $this->locationService = $locationService;
        $this->parserTools = $parserTools;
    }

    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \EzSystems\EzPlatformRest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \eZ\Publish\API\Repository\Values\Content\LocationCreateStruct
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('ParentLocation', $data) || !is_array($data['ParentLocation'])) {
            throw new Exceptions\Parser("Missing or invalid 'ParentLocation' element for LocationCreate.");
        }

        if (!array_key_exists('_href', $data['ParentLocation'])) {
            throw new Exceptions\Parser("Missing '_href' attribute for ParentLocation element in LocationCreate.");
        }

        $locationHrefParts = explode('/', $this->requestParser->parseHref($data['ParentLocation']['_href'], 'locationPath'));

        $locationCreateStruct = $this->locationService->newLocationCreateStruct(
            array_pop($locationHrefParts)
        );

        if (array_key_exists('priority', $data)) {
            $locationCreateStruct->priority = (int)$data['priority'];
        }

        if (array_key_exists('hidden', $data)) {
            $locationCreateStruct->hidden = $this->parserTools->parseBooleanValue($data['hidden']);
        }

        if (array_key_exists('remoteId', $data)) {
            $locationCreateStruct->remoteId = $data['remoteId'];
        }

        if (!array_key_exists('sortField', $data)) {
            throw new Exceptions\Parser("Missing 'sortField' element for LocationCreate.");
        }

        $locationCreateStruct->sortField = $this->parserTools->parseDefaultSortField($data['sortField']);

        if (!array_key_exists('sortOrder', $data)) {
            throw new Exceptions\Parser("Missing 'sortOrder' element for LocationCreate.");
        }

        $locationCreateStruct->sortOrder = $this->parserTools->parseDefaultSortOrder($data['sortOrder']);

        return $locationCreateStruct;
    }
}
