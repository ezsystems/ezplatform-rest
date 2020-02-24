<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Input\Parser\Criterion;

use EzSystems\EzPlatformRest\Input\BaseParser;
use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\Exceptions;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\IsUserBased as IsUserBasedCriterion;

class IsUserBased extends BaseParser
{
    public function parse(array $data, ParsingDispatcher $parsingDispatcher): IsUserBasedCriterion
    {
        if (!array_key_exists('IsUserBasedCriterion', $data)) {
            throw new Exceptions\Parser('Invalid <IsUserBasedCriterion> format');
        }

        return new IsUserBasedCriterion((bool) $data['IsUserBasedCriterion']);
    }
}
