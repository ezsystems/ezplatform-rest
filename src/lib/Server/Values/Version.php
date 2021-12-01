<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\Content\Content;
use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType;
use Ibexa\Rest\Value as RestValue;

/**
 * Version view model.
 */
class Version extends RestValue
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
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\Relation[]
     */
    public $relations;

    /**
     * Path used to load this content.
     *
     * @var string
     */
    public $path;

    /**
     * Construct.
     *
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Content $content
     * @param \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType $contentType
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Relation[] $relations
     * @param string $path
     */
    public function __construct(Content $content, ContentType $contentType, array $relations, $path = null)
    {
        $this->content = $content;
        $this->contentType = $contentType;
        $this->relations = $relations;
        $this->path = $path;
    }
}

class_alias(Version::class, 'EzSystems\EzPlatformRest\Server\Values\Version');
