<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Values;

use EzSystems\EzPlatformRest\Value as RestValue;

/**
 * RestContentCreateStruct view model.
 */
class RestViewInput extends RestValue
{
    /**
     * The search query.
     *
     * @var \eZ\Publish\API\Repository\Values\Content\Query
     */
    public $query;

    /**
     * View identifier.
     *
     * @var string
     */
    public $identifier;

    /**
     * @var string|null
     */
    public $languageCode;

    /**
     * @var bool|null
     */
    public $useAlwaysAvailable;
}
