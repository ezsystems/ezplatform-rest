<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Rest\Input\BaseParser;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Contracts\Rest\Exceptions;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\UserId as UserIdCriterion;

class UserId extends BaseParser
{
    public function parse(array $data, ParsingDispatcher $parsingDispatcher): UserIdCriterion
    {
        if (!array_key_exists('UserIdCriterion', $data)) {
            throw new Exceptions\Parser('Invalid <UserIdCriterion> format');
        }

        return new UserIdCriterion(explode(',', $data['UserIdCriterion']));
    }
}

class_alias(UserId::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\UserId');
