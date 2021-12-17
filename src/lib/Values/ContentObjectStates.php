<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Values;

use Ibexa\Rest\Value as RestValue;

/**
 * ContentObjectStates view model.
 */
class ContentObjectStates extends RestValue
{
    /**
     * Object states.
     *
     * @var array
     */
    public $states;

    /**
     * Construct.
     *
     * @param array $states
     */
    public function __construct(array $states)
    {
        $this->states = $states;
    }
}

class_alias(ContentObjectStates::class, 'EzSystems\EzPlatformRest\Values\ContentObjectStates');
