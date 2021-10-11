<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Sibling as SiblingCriterion;
use Ibexa\Rest\Input\BaseParser;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Contracts\Rest\Exceptions;

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
        if (!array_key_exists('SiblingCriterion', $data)) {
            throw new Exceptions\Parser('Invalid <SiblingCriterion> format');
        }

        $location = $this->locationService->loadLocation(
            (int)$data['SiblingCriterion']
        );

        return SiblingCriterion::fromLocation($location);
    }
}

class_alias(Sibling::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\Sibling');
