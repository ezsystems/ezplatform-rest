<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\Operator as QueryOperator;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\UserLogin as UserLoginCriterion;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;

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

class_alias(UserLogin::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\UserLogin');
