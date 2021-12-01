<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\Content\Content;
use Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo;
use Ibexa\Contracts\Core\Repository\Values\Content\Location;
use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType;
use Ibexa\Rest\Value as RestValue;

/**
 * REST Content, as received by /content/objects/<ID>.
 *
 * Might have a "Version" (aka Content in the Public API) embedded
 */
class RestContent extends RestValue
{
    /**
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo
     */
    public $contentInfo;

    /**
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\Location
     */
    public $mainLocation;

    /**
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\Content
     */
    public $currentVersion;

    /**
     * @var \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType
     */
    public $contentType;

    /**
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\Relation[]
     */
    public $relations;

    /**
     * Path that was used to load this content.
     *
     * @var string
     */
    public $path;

    /**
     * Construct.
     *
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo $contentInfo
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Location|null $mainLocation
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Content|null $currentVersion
     * @param \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType|null $contentType Can only be null if $currentVersion is
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Relation[]|null $relations Can only be null if $currentVersion is
     * @param string $path
     */
    public function __construct(
        ContentInfo $contentInfo,
        Location $mainLocation = null,
        Content $currentVersion = null,
        ContentType $contentType = null,
        array $relations = null,
        $path = null
    ) {
        $this->contentInfo = $contentInfo;
        $this->mainLocation = $mainLocation;
        $this->currentVersion = $currentVersion;
        $this->contentType = $contentType;
        $this->relations = $relations;
        $this->path = $path;
    }
}

class_alias(RestContent::class, 'EzSystems\EzPlatformRest\Server\Values\RestContent');
