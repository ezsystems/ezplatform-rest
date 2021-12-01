<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\User\UserUpdateStruct;
use Ibexa\Rest\Value as RestValue;

/**
 * RestUserUpdateStruct view model.
 */
class RestUserUpdateStruct extends RestValue
{
    /**
     * User update struct.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\User\UserUpdateStruct
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
     * @param \Ibexa\Contracts\Core\Repository\Values\User\UserUpdateStruct $userUpdateStruct
     * @param mixed $sectionId
     */
    public function __construct(UserUpdateStruct $userUpdateStruct, $sectionId = null)
    {
        $this->userUpdateStruct = $userUpdateStruct;
        $this->sectionId = $sectionId;
    }
}

class_alias(RestUserUpdateStruct::class, 'EzSystems\EzPlatformRest\Server\Values\RestUserUpdateStruct');
