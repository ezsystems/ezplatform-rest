<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo;
use Ibexa\Rest\Server\Output\ValueObjectVisitor;
use Ibexa\Rest\Server\Values\ContentList;
use Ibexa\Rest\Server\Values\RestContent;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;

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

        $contentList = new ContentList([], 0);

        $this->addRouteExpectation(
            'ezpublish_rest_redirectContent',
            [],
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
            [
                'tag' => 'ContentList',
            ],
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
            [
                'tag' => 'ContentList',
                'attributes' => [
                    'media-type' => 'application/vnd.ez.api.ContentList+xml',
                    'href' => '/content/objects',
                ],
            ],
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
            [
                new RestContent(new ContentInfo()),
                new RestContent(new ContentInfo()),
            ],
            2
        );

        $this->getVisitorMock()->expects($this->exactly(2))
            ->method('visitValueObject')
            ->with($this->isInstanceOf(RestContent::class));

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $contentList
        );

        return $generator->endDocument(null);
    }

    /**
     * Test if result contains ContentList element attributes.
     *
     * @param string $result
     *
     * @depends testContentListVisitsChildren
     */
    public function testResultContainsTotalCountAttributes(string $result): void
    {
        $this->assertXMLTag(
            [
                'tag' => 'ContentList',
                'attributes' => [
                    'totalCount' => 2,
                ],
            ],
            $result,
            'Invalid <ContentList> totalCount attribute.',
            false
        );
    }

    /**
     * Get the ContentList visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\ContentList
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\ContentList();
    }
}

class_alias(ContentListTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\ContentListTest');
