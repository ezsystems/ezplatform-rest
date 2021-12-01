<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\SectionIdentifier as SectionIdentifierCriterion;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;

class SectionIdentifier extends BaseParser
{
    public function parse(array $data, ParsingDispatcher $parsingDispatcher): SectionIdentifierCriterion
    {
        if (!array_key_exists('SectionIdentifierCriterion', $data)) {
            throw new Exceptions\Parser('Invalid <SectionIdentifierCriterion> format');
        }

        return new SectionIdentifierCriterion(explode(',', $data['SectionIdentifierCriterion']));
    }
}

class_alias(SectionIdentifier::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\SectionIdentifier');
