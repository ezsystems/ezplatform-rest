<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

/**
 * Country list view model.
 */
class CountryList extends RestValue
{
    /**
     * @var \Ibexa\Core\Repository\Values\ContentType\Countries[]
     */
    public $countries;

    /**
     * Construct.
     */
    public function __construct(array $countries)
    {
        $this->countries = $countries;
    }
}

class_alias(CountryList::class, 'EzSystems\EzPlatformRest\Server\Values\CountryList');
