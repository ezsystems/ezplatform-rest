<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Values;

use eZ\Publish\API\Repository\Values\ObjectState\ObjectState;
use Ibexa\Rest\Value as RestValue;

/**
 * This class wraps the object state with added groupId property.
 */
class RestObjectState extends RestValue
{
    /**
     * Wrapped object state.
     *
     * @var \eZ\Publish\API\Repository\Values\ObjectState\ObjectState
     */
    public $objectState;

    /**
     * Group ID to which wrapped state belongs.
     *
     * @var mixed
     */
    public $groupId;

    /**
     * Constructor.
     *
     * @param \eZ\Publish\API\Repository\Values\ObjectState\ObjectState $objectState
     * @param mixed $groupId
     */
    public function __construct(ObjectState $objectState, $groupId)
    {
        $this->objectState = $objectState;
        $this->groupId = $groupId;
    }
}

class_alias(RestObjectState::class, 'EzSystems\EzPlatformRest\Values\RestObjectState');
