<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\Content\VersionInfo;
use Ibexa\Rest\Value as RestValue;

/**
 * Version Translations information view model.
 */
class VersionTranslationInfo extends RestValue
{
    /**
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\VersionInfo
     */
    private $versionInfo;

    /**
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\VersionInfo $versionInfo
     */
    public function __construct(VersionInfo $versionInfo)
    {
        $this->versionInfo = $versionInfo;
    }

    /**
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\VersionInfo
     */
    public function getVersionInfo()
    {
        return $this->versionInfo;
    }
}

class_alias(VersionTranslationInfo::class, 'EzSystems\EzPlatformRest\Server\Values\VersionTranslationInfo');
