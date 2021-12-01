<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Contracts\Core\Repository\LocationService;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\Sibling as SiblingCriterion;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;

class Sibling extends BaseParser
{
    /** @var \Ibexa\Contracts\Core\Repository\LocationService */
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
