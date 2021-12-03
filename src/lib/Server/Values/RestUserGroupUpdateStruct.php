<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\User\UserGroupUpdateStruct;
use Ibexa\Rest\Value as RestValue;

/**
 * RestUserGroupUpdateStruct view model.
 */
class RestUserGroupUpdateStruct extends RestValue
{
    /**
     * UserGroup update struct.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\User\UserGroupUpdateStruct
     */
    public $userGroupUpdateStruct;

    /**
     * If set, section of the UserGroup will be updated.
     *
     * @var mixed
     */
    public $sectionId;

    /**
     * Construct.
     *
     * @param \Ibexa\Contracts\Core\Repository\Values\User\UserGroupUpdateStruct $userGroupUpdateStruct
     * @param mixed $sectionId
     */
    public function __construct(UserGroupUpdateStruct $userGroupUpdateStruct, $sectionId = null)
    {
        $this->userGroupUpdateStruct = $userGroupUpdateStruct;
        $this->sectionId = $sectionId;
    }
}

class_alias(RestUserGroupUpdateStruct::class, 'EzSystems\EzPlatformRest\Server\Values\RestUserGroupUpdateStruct');
