<?php

/**
 * File containing the RestUserUpdateStruct class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Values;

use eZ\Publish\API\Repository\Values\User\UserUpdateStruct;
use EzSystems\EzPlatformRest\Value as RestValue;

/**
 * RestUserUpdateStruct view model.
 */
class RestUserUpdateStruct extends RestValue
{
    /**
     * User update struct.
     *
     * @var \eZ\Publish\API\Repository\Values\User\UserUpdateStruct
     */
    public $userUpdateStruct;

    /**
     * If set, section of the User will be updated.
     *
     * @var mixed
     */
    public $sectionId;

    /**
     * Construct.
     *
     * @param \eZ\Publish\API\Repository\Values\User\UserUpdateStruct $userUpdateStruct
     * @param mixed $sectionId
     */
    public function __construct(UserUpdateStruct $userUpdateStruct, $sectionId = null)
    {
        $this->userUpdateStruct = $userUpdateStruct;
        $this->sectionId = $sectionId;
    }
}
