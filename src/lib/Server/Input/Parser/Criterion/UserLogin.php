<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Input\Parser\Criterion;

use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Operator as QueryOperator;
use EzSystems\EzPlatformRest\Input\BaseParser;
use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\Exceptions;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\UserLogin as UserLoginCriterion;

class UserLogin extends BaseParser
{
    public function parse(array $data, ParsingDispatcher $parsingDispatcher): UserLoginCriterion
    {
        if (!array_key_exists('UserLoginCriterion', $data)) {
            throw new Exceptions\Parser('Invalid <UserLoginCriterion> format');
        }

        $value = explode(',', $data['UserLoginCriterion']);

        if (count($value) === 1) {
            $value = reset($value);
            $operator = strpos($value, '*') !== false
                ? QueryOperator::LIKE
                : QueryOperator::EQ;
        } else {
            $operator = QueryOperator::IN;
        }

        return new UserLoginCriterion($value, $operator);
    }
}
