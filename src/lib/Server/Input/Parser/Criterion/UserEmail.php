<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Operator as QueryOperator;
use Ibexa\Rest\Input\BaseParser;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Contracts\Rest\Exceptions;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\UserEmail as UserEmailCriterion;

class UserEmail extends BaseParser
{
    public function parse(array $data, ParsingDispatcher $parsingDispatcher): UserEmailCriterion
    {
        if (!array_key_exists('UserEmailCriterion', $data)) {
            throw new Exceptions\Parser('Invalid <UserEmailCriterion> format');
        }

        $value = explode(',', $data['UserEmailCriterion']);

        if (count($value) === 1) {
            $value = reset($value);
            $operator = strpos($value, '*') !== false
                ? QueryOperator::LIKE
                : QueryOperator::EQ;
        } else {
            $operator = QueryOperator::IN;
        }

        return new UserEmailCriterion($value, $operator);
    }
}

class_alias(UserEmail::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\UserEmail');
