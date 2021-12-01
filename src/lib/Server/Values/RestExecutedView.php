<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\ValueObject;

/**
 * RestContentCreateStruct view model.
 */
class RestExecutedView extends ValueObject
{
    /**
     * The search results.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\Search\SearchResult
     */
    public $searchResults;

    /**
     * The view identifier.
     *
     * @var mixed
     */
    public $identifier;
}

class_alias(RestExecutedView::class, 'EzSystems\EzPlatformRest\Server\Values\RestExecutedView');
