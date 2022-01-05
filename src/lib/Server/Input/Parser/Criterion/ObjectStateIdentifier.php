<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\ObjectStateIdentifier as ObjectStateIdentifierCriterion;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;

class ObjectStateIdentifier extends BaseParser
{
    public function parse(array $data, ParsingDispatcher $parsingDispatcher): ObjectStateIdentifierCriterion
    {
        if (
            !array_key_exists('ObjectStateIdentifierCriterion', $data)
            || !array_key_exists('value', $data['ObjectStateIdentifierCriterion'])
            || !array_key_exists('target', $data['ObjectStateIdentifierCriterion'])
        ) {
            throw new Exceptions\Parser('Invalid <ObjectStateIdentifierCriterion> format');
        }

        $target = !empty($data['ObjectStateIdentifierCriterion']['target'])
            ? $data['ObjectStateIdentifierCriterion']['target']
            : null;

        return new ObjectStateIdentifierCriterion(
            explode(',', $data['ObjectStateIdentifierCriterion']['value']),
            $target
        );
    }
}

class_alias(ObjectStateIdentifier::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\ObjectStateIdentifier');
