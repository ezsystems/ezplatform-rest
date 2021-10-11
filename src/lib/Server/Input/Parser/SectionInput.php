<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser;

use Ibexa\Rest\Input\BaseParser;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use eZ\Publish\API\Repository\SectionService;
use Ibexa\Contracts\Rest\Exceptions;

/**
 * Parser for SectionInput.
 */
class SectionInput extends BaseParser
{
    /**
     * Section service.
     *
     * @var \eZ\Publish\API\Repository\SectionService
     */
    protected $sectionService;

    /**
     * Construct.
     *
     * @param \eZ\Publish\API\Repository\SectionService $sectionService
     */
    public function __construct(SectionService $sectionService)
    {
        $this->sectionService = $sectionService;
    }

    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \EzSystems\EzPlatformRest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \eZ\Publish\API\Repository\Values\Content\SectionCreateStruct
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $sectionCreate = $this->sectionService->newSectionCreateStruct();

        //@todo XSD says that name is not mandatory? Does that make sense?
        if (!array_key_exists('name', $data)) {
            throw new Exceptions\Parser("Missing 'name' attribute for SectionInput.");
        }

        $sectionCreate->name = $data['name'];

        //@todo XSD says that identifier is not mandatory? Does that make sense?
        if (!array_key_exists('identifier', $data)) {
            throw new Exceptions\Parser("Missing 'identifier' attribute for SectionInput.");
        }

        $sectionCreate->identifier = $data['identifier'];

        return $sectionCreate;
    }
}

class_alias(SectionInput::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\SectionInput');
