<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Contracts\Rest\Input;

/**
 * Base class for input parser.
 */
abstract class Parser
{
    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \EzSystems\EzPlatformRest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \eZ\Publish\API\Repository\Values\ValueObject
     */
    abstract public function parse(array $data, ParsingDispatcher $parsingDispatcher);
}

class_alias(Parser::class, 'EzSystems\EzPlatformRest\Input\Parser');
