<?php

/**
 * File containing the LocationUpdate parser class.
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
use EzSystems\EzPlatformRest\Server\Values\RestLocationUpdateStruct;

/**
 * Parser for LocationUpdate.
 */
class LocationUpdate extends BaseParser
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
     * @return \EzSystems\EzPlatformRest\Server\Values\RestLocationUpdateStruct
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $locationUpdateStruct = $this->locationService->newLocationUpdateStruct();

        if (array_key_exists('priority', $data)) {
            $locationUpdateStruct->priority = (int)$data['priority'];
        }

        if (array_key_exists('remoteId', $data)) {
            $locationUpdateStruct->remoteId = $data['remoteId'];
        }

        $hidden = null;
        if (array_key_exists('hidden', $data)) {
            $hidden = $this->parserTools->parseBooleanValue($data['hidden']);
        }

        if (!array_key_exists('sortField', $data)) {
            throw new Exceptions\Parser("Missing 'sortField' element for LocationUpdate.");
        }

        $locationUpdateStruct->sortField = $this->parserTools->parseDefaultSortField($data['sortField']);

        if (!array_key_exists('sortOrder', $data)) {
            throw new Exceptions\Parser("Missing 'sortOrder' element for LocationUpdate.");
        }

        $locationUpdateStruct->sortOrder = $this->parserTools->parseDefaultSortOrder($data['sortOrder']);

        return new RestLocationUpdateStruct($locationUpdateStruct, $hidden);
    }
}
