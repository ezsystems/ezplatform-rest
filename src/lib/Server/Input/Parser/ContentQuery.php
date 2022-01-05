<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\Values\Content\Query;
use Ibexa\Rest\Server\Input\Parser\Query as QueryParser;

/**
 * Parser for ViewInput.
 */
class ContentQuery extends QueryParser
{
    protected function buildQuery()
    {
        return new Query();
    }
}

class_alias(ContentQuery::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\ContentQuery');
