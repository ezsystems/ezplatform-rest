<?php

/**
 * File containing a test class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Tests\Output\ValueObjectVisitorBaseTest;
use EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Server\Values\ContentList;
use EzSystems\EzPlatformRest\Server\Values\RestContent;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;

class ContentListTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the ContentList visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $contentList = new ContentList(array());

        $this->addRouteExpectation(
            'ezpublish_rest_redirectContent',
            array(),
            '/content/objects'
        );

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $contentList
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        return $result;
    }

    /**
     * Test if result contains ContentList element.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsContentListElement($result)
    {
        $this->assertXMLTag(
            array(
                'tag' => 'ContentList',
            ),
            $result,
            'Invalid <ContentList> element.',
            false
        );
    }

    /**
     * Test if result contains ContentList element attributes.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsContentListAttributes($result)
    {
        $this->assertXMLTag(
            array(
                'tag' => 'ContentList',
                'attributes' => array(
                    'media-type' => 'application/vnd.ez.api.ContentList+xml',
                    'href' => '/content/objects',
                ),
            ),
            $result,
            'Invalid <ContentList> attributes.',
            false
        );
    }

    /**
     * Test if ContentList visitor visits the children.
     */
    public function testContentListVisitsChildren()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $contentList = new ContentList(
            array(
                new RestContent(new ContentInfo()),
                new RestContent(new ContentInfo()),
            )
        );

        $this->getVisitorMock()->expects($this->exactly(2))
            ->method('visitValueObject')
            ->with($this->isInstanceOf(RestContent::class));

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $contentList
        );
    }

    /**
     * Get the ContentList visitor.
     *
     * @return \EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\ContentList
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\ContentList();
    }
}
