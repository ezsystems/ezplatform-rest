<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Input\Parser;

use Ibexa\Contracts\Rest\Exceptions;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Core\Repository\Values\ObjectState\ObjectState;
use Ibexa\Rest\Input\BaseParser;
use Ibexa\Rest\Values\RestObjectState;

/**
 * Parser for ContentObjectStates.
 */
class ContentObjectStates extends BaseParser
{
    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \Ibexa\Contracts\Rest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \Ibexa\Rest\Values\RestObjectState[]
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        // @todo XSD says that no ObjectState elements is valid,
        // but we should at least have one if setting new states to content?
        if (!array_key_exists('ObjectState', $data) || !is_array($data['ObjectState']) || empty($data['ObjectState'])) {
            throw new Exceptions\Parser("Missing or invalid 'ObjectState' elements for ContentObjectStates.");
        }

        $states = [];
        foreach ($data['ObjectState'] as $rawStateData) {
            if (!array_key_exists('_href', $rawStateData)) {
                throw new Exceptions\Parser("Missing '_href' attribute for ObjectState.");
            }

            $states[] = new RestObjectState(
                new ObjectState(
                    [
                        'id' => $this->requestParser->parseHref($rawStateData['_href'], 'objectStateId'),
                    ]
                ),
                $this->requestParser->parseHref($rawStateData['_href'], 'objectStateGroupId')
            );
        }

        return $states;
    }
}

class_alias(ContentObjectStates::class, 'EzSystems\EzPlatformRest\Input\Parser\ContentObjectStates');
