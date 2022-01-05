<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Controller;

use Ibexa\Rest\Server\Controller as RestController;
use Ibexa\Rest\Server\Values;

/**
 * Services controller.
 */
class Services extends RestController
{
    /**
     * @var array
     */
    protected $countriesInfo;

    public function __construct(array $countriesInfo)
    {
        $this->countriesInfo = $countriesInfo;
    }

    /**
     * Loads Country List.
     */
    public function loadCountryList()
    {
        return new Values\CountryList($this->countriesInfo);
    }
}

class_alias(Services::class, 'EzSystems\EzPlatformRest\Server\Controller\Services');
