<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Rest\Output\ValueObjectVisitor;
use Ibexa\Rest\Values\ContentObjectStates;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;

class ContentObjectStatesTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the ContentObjectStates visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        // @todo Improve this test with values...
        $stateList = new ContentObjectStates([]);

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $stateList
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        return $result;
    }

    /**
     * Test if result contains ContentObjectStates element.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsContentObjectStatesElement($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'ContentObjectStates',
            ],
            $result,
            'Invalid <ContentObjectStates> element.',
            false
        );
    }

    /**
     * Test if result contains ContentObjectStates element attributes.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsContentObjectStatesAttributes($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'ContentObjectStates',
                'attributes' => [
                    'media-type' => 'application/vnd.ez.api.ContentObjectStates+xml',
                ],
            ],
            $result,
            'Invalid <ContentObjectStates> attributes.',
            false
        );
    }

    /**
     * Get the ContentObjectStates visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\ContentObjectStates
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\ContentObjectStates();
    }
}

class_alias(ContentObjectStatesTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\ContentObjectStatesTest');
