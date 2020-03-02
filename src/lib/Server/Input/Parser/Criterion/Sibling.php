<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Input\Parser\Criterion;

use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Sibling as SiblingCriterion;
use EzSystems\EzPlatformRest\Input\BaseParser;
use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\Exceptions;

class Sibling extends BaseParser
{
    /** @var \eZ\Publish\API\Repository\LocationService */
    private $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function parse(array $data, ParsingDispatcher $parsingDispatcher): SiblingCriterion
    {
        if (!array_key_exists('SiblingsCriterion', $data)) {
            throw new Exceptions\Parser('Invalid <SiblingCriterion> format');
        }

        $location = $this->locationService->loadLocation(
            (int)$data['SiblingsCriterion']
        );

        return SiblingCriterion::fromLocation($location);
    }
}
