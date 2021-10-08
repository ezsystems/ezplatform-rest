<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser;

use Ibexa\Rest\Input\BaseParser;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\ParserTools;
use Ibexa\Rest\Exceptions;
use eZ\Publish\API\Repository\ObjectStateService;

/**
 * Parser for ObjectStateUpdate.
 */
class ObjectStateUpdate extends BaseParser
{
    /**
     * Object state service.
     *
     * @var \eZ\Publish\API\Repository\ObjectStateService
     */
    protected $objectStateService;

    /**
     * @var \EzSystems\EzPlatformRest\Input\ParserTools
     */
    protected $parserTools;

    /**
     * Construct.
     *
     * @param \eZ\Publish\API\Repository\ObjectStateService $objectStateService
     * @param \EzSystems\EzPlatformRest\Input\ParserTools $parserTools
     */
    public function __construct(ObjectStateService $objectStateService, ParserTools $parserTools)
    {
        $this->objectStateService = $objectStateService;
        $this->parserTools = $parserTools;
    }

    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \EzSystems\EzPlatformRest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \eZ\Publish\API\Repository\Values\ObjectState\ObjectStateUpdateStruct
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $objectStateUpdateStruct = $this->objectStateService->newObjectStateUpdateStruct();

        if (array_key_exists('identifier', $data)) {
            $objectStateUpdateStruct->identifier = $data['identifier'];
        }

        if (array_key_exists('defaultLanguageCode', $data)) {
            $objectStateUpdateStruct->defaultLanguageCode = $data['defaultLanguageCode'];
        }

        if (array_key_exists('names', $data)) {
            if (!is_array($data['names'])) {
                throw new Exceptions\Parser("Missing or invalid 'names' element for ObjectStateUpdate.");
            }

            if (!array_key_exists('value', $data['names']) || !is_array($data['names']['value'])) {
                throw new Exceptions\Parser("Missing or invalid 'names' element for ObjectStateUpdate.");
            }

            $objectStateUpdateStruct->names = $this->parserTools->parseTranslatableList($data['names']);
        }

        if (array_key_exists('descriptions', $data) && is_array($data['descriptions'])) {
            $objectStateUpdateStruct->descriptions = $this->parserTools->parseTranslatableList($data['descriptions']);
        }

        return $objectStateUpdateStruct;
    }
}

class_alias(ObjectStateUpdate::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\ObjectStateUpdate');
