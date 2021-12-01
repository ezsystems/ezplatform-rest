<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Contracts\Rest\Input;

use Ibexa\Contracts\Rest\Exceptions;

/**
 * Parsing dispatcher.
 */
class ParsingDispatcher
{
    /**
     * Array of parsers.
     *
     * Structure:
     *
     * <code>
     *  array(
     *      <contentType> => array(
     *          <version> => <parser>,
     *          …
     *      }
     *  )
     * </code>
     *
     * @var \Ibexa\Contracts\Rest\Input\Parser[]
     */
    protected $parsers = [];

    /**
     * Construct from optional parsers array.
     *
     * @param array $parsers
     */
    public function __construct(array $parsers = [])
    {
        foreach ($parsers as $mediaType => $parser) {
            $this->addParser($mediaType, $parser);
        }
    }

    /**
     * Adds another parser for the given Content Type.
     *
     * @param string $mediaType
     * @param \Ibexa\Contracts\Rest\Input\Parser $parser
     */
    public function addParser($mediaType, Parser $parser)
    {
        list($mediaType, $version) = $this->parseMediaTypeVersion($mediaType);
        $this->parsers[$mediaType][$version] = $parser;
    }

    /**
     * Parses the given $data according to $mediaType.
     *
     * @param array $data
     * @param string $mediaType
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\ValueObject
     */
    public function parse(array $data, $mediaType)
    {
        list($mediaType, $version) = $this->parseMediaTypeVersion($mediaType);

        // Remove encoding type
        if (($plusPos = strrpos($mediaType, '+')) !== false) {
            $mediaType = substr($mediaType, 0, $plusPos);
        }

        if (!isset($this->parsers[$mediaType][$version])) {
            throw new Exceptions\Parser("Unknown Content Type specification: '{$mediaType} (version: $version)'.");
        }

        return $this->parsers[$mediaType][$version]->parse($data, $this);
    }

    /**
     * Parses and returns the version from a MediaType.
     *
     * @param string $mediaType Ex: text/html; version=1.1
     *
     * @return array An array with the mediatype string, stripped from the version, and the version (1.0 by default)
     */
    protected function parseMediaTypeVersion($mediaType)
    {
        $version = '1.0';
        $contentType = explode('; ', $mediaType);
        if (count($contentType) > 1) {
            $mediaType = $contentType[0];
            foreach (array_slice($contentType, 1) as $parameterString) {
                if (strpos($contentType[1], '=') === false) {
                    throw new Exceptions\Parser("Unknown parameter format: '{$parameterString}'");
                }
                list($parameterName, $parameterValue) = explode('=', $parameterString);
                if (trim($parameterName) === 'version') {
                    $version = trim($parameterValue);
                    break;
                }
            }
        }

        return [$mediaType, $version];
    }
}

class_alias(ParsingDispatcher::class, 'EzSystems\EzPlatformRest\Input\ParsingDispatcher');
