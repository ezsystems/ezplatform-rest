<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Contracts\Core\Repository\LocationService;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\ParentLocationId as ParentLocationIdCriterion;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;

/**
 * Parser for ParentLocationId Criterion.
 */
class ParentLocationRemoteId extends BaseParser
{
    /**
     * Location service.
     *
     * @var \Ibexa\Contracts\Core\Repository\LocationService
     */
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Parses input structure to a Criterion object.
     *
     * @param array $data
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \Ibexa\Contracts\Rest\Exceptions\Parser
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\ParentLocationId
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('ParentLocationRemoteIdCriterion', $data)) {
            throw new Exceptions\Parser('Invalid <ParentLocationRemoteIdCriterion> format');
        }
        $contentIdArray = [];
        foreach (explode(',', $data['ParentLocationRemoteIdCriterion']) as $parentRemoteId) {
            $location = $this->locationService->loadLocationByRemoteId($parentRemoteId);
            $contentIdArray[] = $location->id;
        }

        return new ParentLocationIdCriterion($contentIdArray);
    }
}

class_alias(ParentLocationRemoteId::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\ParentLocationRemoteId');
