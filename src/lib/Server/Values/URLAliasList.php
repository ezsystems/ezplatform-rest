<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

/**
 * URLAlias list view model.
 */
class URLAliasList extends RestValue
{
    /**
     * URL aliases.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\URLAlias[]
     */
    public $urlAliases;

    /**
     * Path used to load the list of URL aliases.
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

class_alias(URLAliasList::class, 'EzSystems\EzPlatformRest\Server\Values\URLAliasList');
