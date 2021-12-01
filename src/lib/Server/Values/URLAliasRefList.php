<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

/**
 * URLAlias ref list view model.
 */
class URLAliasRefList extends RestValue
{
    /**
     * URL aliases.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\URLAlias[]
     */
    public $urlAliases;

    /**
     * Path that was used to fetch the list of URL aliases.
     *
     * @var string
     */
    public $path;

    /**
     * Construct.
     *
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\URLAlias[] $urlAliases
     * @param string $path
     */
    public function __construct(array $urlAliases, $path)
    {
        $this->urlAliases = $urlAliases;
        $this->path = $path;
    }
}

class_alias(URLAliasRefList::class, 'EzSystems\EzPlatformRest\Server\Values\URLAliasRefList');
