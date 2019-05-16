<?php

/**
 * File containing the ContentUpdate parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Input\Parser;

use EzSystems\EzPlatformRest\Input\BaseParser;
use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\Exceptions;
use EzSystems\EzPlatformRest\Values\RestContentMetadataUpdateStruct;
use DateTime;
use Exception;

/**
 * Parser for ContentUpdate.
 */
class ContentUpdate extends BaseParser
{
    /**
     * Parse input structure.
     *
     * @todo use url handler instead of hardcoded URL matching
     *
     * @param array $data
     * @param \EzSystems\EzPlatformRest\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \EzSystems\EzPlatformRest\Values\RestContentMetadataUpdateStruct
     *
     * @throws \EzSystems\EzPlatformRest\Exceptions\Parser if $data is invalid
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $parsedData = array();

        if (array_key_exists('Section', $data) && is_array($data['Section']) && isset($data['Section']['_href'])) {
            try {
                $parsedData['sectionId'] = $this->requestParser->parseHref($data['Section']['_href'], 'sectionId');
            } catch (Exceptions\InvalidArgumentException $e) {
                throw new Exceptions\Parser('Invalid format for <Section> reference in <ContentUpdate>.');
            }
        }

        if (array_key_exists('Owner', $data) && is_array($data['Owner']) && isset($data['Owner']['_href'])) {
            try {
                $parsedData['ownerId'] = $this->requestParser->parseHref($data['Owner']['_href'], 'userId');
            } catch (Exceptions\InvalidArgumentException $e) {
                throw new Exceptions\Parser('Invalid format for <Owner> reference in <ContentUpdate>.');
            }
        }

        if (array_key_exists('mainLanguageCode', $data)) {
            $parsedData['mainLanguageCode'] = $data['mainLanguageCode'];
        }

        if (array_key_exists('MainLocation', $data)) {
            try {
                $mainLocationIdParts = explode('/', $this->requestParser->parseHref($data['MainLocation']['_href'], 'locationPath'));
                $parsedData['mainLocationId'] = array_pop($mainLocationIdParts);
            } catch (Exceptions\InvalidArgumentException $e) {
                throw new Exceptions\Parser('Invalid format for <MainLocation> reference in <ContentUpdate>.');
            }
        }

        if (array_key_exists('alwaysAvailable', $data)) {
            if ($data['alwaysAvailable'] === 'true') {
                $parsedData['alwaysAvailable'] = true;
            } elseif ($data['alwaysAvailable'] === 'false') {
                $parsedData['alwaysAvailable'] = false;
            } else {
                throw new Exceptions\Parser('Invalid format for <alwaysAvailable> in <ContentUpdate>.');
            }
        }

        // remoteId
        if (array_key_exists('remoteId', $data)) {
            $parsedData['remoteId'] = $data['remoteId'];
        }

        // modificationDate
        if (array_key_exists('modificationDate', $data)) {
            try {
                $parsedData['modificationDate'] = new DateTime($data['modificationDate']);
            } catch (Exception $e) {
                throw new Exceptions\Parser('Invalid format for <modificationDate> in <ContentUpdate>', 0, $e);
            }
        }

        // publishDate
        if (array_key_exists('publishDate', $data)) {
            try {
                $parsedData['publishedDate'] = new DateTime($data['publishDate']);
            } catch (Exception $e) {
                throw new Exceptions\Parser('Invalid format for <publishDate> in <ContentUpdate>', 0, $e);
            }
        }

        return new RestContentMetadataUpdateStruct($parsedData);
    }
}
