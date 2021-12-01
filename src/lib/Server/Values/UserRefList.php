<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

/**
 * User list view model.
 */
class UserRefList extends RestValue
{
    /**
     * Users.
     *
     * @var \Ibexa\Rest\Server\Values\RestUser[]
     */
    public $users;

    /**
     * Path which was used to fetch the list of users.
     *
     * @var string
     */
    public $path;

    /**
     * Construct.
     *
     * @param \Ibexa\Rest\Server\Values\RestUser[] $users
     * @param string $path
     */
    public function __construct(array $users, $path)
    {
        $this->users = $users;
        $this->path = $path;
    }
}

class_alias(UserRefList::class, 'EzSystems\EzPlatformRest\Server\Values\UserRefList');
