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
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\IsUserEnabled as IsUserEnabledCriterion;

class IsUserEnabled extends BaseParser
{
    public function parse(array $data, ParsingDispatcher $parsingDispatcher): IsUserEnabledCriterion
    {
        if (!array_key_exists('IsUserEnabledCriterion', $data)) {
            throw new Exceptions\Parser('Invalid <IsUserEnabledCriterion> format');
        }

        return new IsUserEnabledCriterion((bool) $data['IsUserEnabledCriterion']);
    }
}
