<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

/**
 * RestContentCreateStruct view model.
 */
class RestViewInput extends RestValue
{
    /**
     * The search query.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\Query
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

class_alias(RestViewInput::class, 'EzSystems\EzPlatformRest\Server\Values\RestViewInput');
