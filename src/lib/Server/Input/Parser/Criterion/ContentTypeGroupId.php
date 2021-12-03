<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\ContentTypeGroupId as ContentTypeGroupIdCriterion;
use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\BaseParser;

/**
 * Parser for ContentTypeGroupId Criterion.
 */
class ContentTypeGroupId extends BaseParser
{
    /**
     * Parses input structure to a Criterion object.
     *
     * @param array $data
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \Ibexa\Contracts\Rest\Exceptions\Parser
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\ContentTypeGroupId
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('ContentTypeGroupIdCriterion', $data)) {
            throw new Exceptions\Parser('Invalid <ContentTypeGroupIdCriterion> format');
        }

        return new ContentTypeGroupIdCriterion($data['ContentTypeGroupIdCriterion']);
    }
}

class_alias(ContentTypeGroupId::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\ContentTypeGroupId');
