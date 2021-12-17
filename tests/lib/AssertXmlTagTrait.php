<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest;

use DOMDocument;
use DOMXPath;

/**
 * Trait AssertXmlTagTrait.
 *
 * @private Only for use internally in REST tests
 */
trait AssertXmlTagTrait
{
    /**
     * Simple re implementation of assertTag (deprecated in PHPUnit) for XML use.
     *
     * @param array $matcher Hash with 'tag' (required), and optionally: 'attributes' & 'content' keys
     * @param string $actualXml
     * @param string $message
     */
    public static function assertXMLTag($matcher, $actualXml, $message = '')
    {
        // Provide default values.
        $matcher += ['attributes' => []];

        // Create an XPath query that selects the xml tag.
        $query = '//' . $matcher['tag'];

        // Append XPath selectors for the attributes and content text.
        $selectors = [];
        foreach ($matcher['attributes'] as $attribute => $value) {
            $selectors[] = "@$attribute='$value'";
        }

        if (!empty($matcher['content'])) {
            $selectors[] = "contains(.,'{$matcher['content']}')";
        }

        if (!empty($selectors)) {
            $query .= '[' . implode(' and ', $selectors) . ']';
        }

        // Execute the query.
        $document = new DOMDocument();
        $document->loadXML($actualXml);
        $xpath = new DOMXPath($document);

        self::assertGreaterThanOrEqual(1, $xpath->query($query)->length, $message);
    }
}

class_alias(AssertXmlTagTrait::class, 'EzSystems\EzPlatformRest\Tests\AssertXmlTagTrait');
