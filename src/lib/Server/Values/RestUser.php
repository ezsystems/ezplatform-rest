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
 * REST User, as received by /user/users/<ID>.
 */
class RestUser extends RestValue
{
    /**
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\Content
     */
    public $content;

    /**
     * @var \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType
     */
    public $contentType;

    /**
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo
     */
    public $contentInfo;

    /**
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\Relation[]
     */
    public $relations;

    /**
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\Location
     */
    public $mainLocation;

    /**
     * Construct.
     *
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Content $content
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo $contentInfo
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Location $mainLocation
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Relation[] $relations
     */
    public function __construct(Content $content, ContentType $contentType, ContentInfo $contentInfo, Location $mainLocation, array $relations)
    {
        $this->content = $content;
        $this->contentType = $contentType;
        $this->contentInfo = $contentInfo;
        $this->mainLocation = $mainLocation;
        $this->relations = $relations;
    }
}

class_alias(RestUser::class, 'EzSystems\EzPlatformRest\Server\Values\RestUser');
