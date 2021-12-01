<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

/**
 * Version list view model.
 */
class VersionList extends RestValue
{
    /**
     * Versions.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\VersionInfo[]
     */
    public $versions;

    /**
     * Path used to retrieve this version list.
     *
     * @var string
     */
    public $path;

    /**
     * Construct.
     *
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\VersionInfo[] $versions
     * @param string $path
     */
    public function __construct(array $versions, $path)
    {
        $this->versions = $versions;
        $this->path = $path;
    }
}

class_alias(VersionList::class, 'EzSystems\EzPlatformRest\Server\Values\VersionList');
