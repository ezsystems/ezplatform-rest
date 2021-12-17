<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\SectionService;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;

/**
 * Parser for SectionInput.
 */
class SectionInput extends BaseParser
{
    /**
     * Section service.
     *
     * @var \Ibexa\Contracts\Core\Repository\SectionService
     */
    protected $sectionService;

    /**
     * Construct.
     *
     * @param \Ibexa\Contracts\Core\Repository\SectionService $sectionService
     */
    public function __construct(SectionService $sectionService)
    {
        $this->sectionService = $sectionService;
    }

    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\SectionCreateStruct
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
