<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use eZ\Publish\API\Repository\Values\ValueObject;

/**
 * RestContentCreateStruct view model.
 */
class RestExecutedView extends ValueObject
{
    /**
     * The search results.
     *
     * @var \eZ\Publish\API\Repository\Values\Content\Search\SearchResult
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
