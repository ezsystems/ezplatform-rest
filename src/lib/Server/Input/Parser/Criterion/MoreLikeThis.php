<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Input\Parser\Criterion;

use Ibexa\Rest\Input\BaseParser;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;

/**
 * Parser for MoreLikeThis Criterion.
 */
class MoreLikeThis extends BaseParser
{
    /**
     * Parses input structure to a Criterion object.
     *
     * @param array $data
     * @param \EzSystems\EzPlatformRest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \EzSystems\EzPlatformRest\Exceptions\Parser
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Query\Criterion\MoreLikeThis
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        throw new \Exception('@todo implement');
    }
}

class_alias(MoreLikeThis::class, 'EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\MoreLikeThis');
