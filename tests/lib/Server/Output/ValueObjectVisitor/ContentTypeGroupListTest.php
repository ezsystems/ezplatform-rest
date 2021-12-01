<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentTypeGroup;
use Ibexa\Core\Repository\Values\ContentType;
use Ibexa\Rest\Server\Output\ValueObjectVisitor;
use Ibexa\Rest\Server\Values\ContentTypeGroupList;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;

class ContentTypeGroupListTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the ContentTypeGroupList visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $contentTypeGroupList = new ContentTypeGroupList([]);

        $this->addRouteExpectation('ezpublish_rest_loadContentTypeGroupList', [], '/content/typegroups');

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $contentTypeGroupList
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        return $result;
    }

    /**
     * Test if result contains ContentTypeGroupList element.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsContentTypeGroupListElement($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'ContentTypeGroupList',
            ],
            $result,
            'Invalid <ContentTypeGroupList> element.',
            false
        );
    }

    /**
     * Test if result contains ContentTypeGroupList element attributes.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsContentTypeGroupListAttributes($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'ContentTypeGroupList',
                'attributes' => [
                    'media-type' => 'application/vnd.ez.api.ContentTypeGroupList+xml',
                    'href' => '/content/typegroups',
                ],
            ],
            $result,
            'Invalid <ContentTypeGroupList> attributes.',
            false
        );
    }

    /**
     * Test if ContentTypeGroupList visitor visits the children.
     */
    public function testContentTypeGroupListVisitsChildren()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $contentTypeGroupList = new ContentTypeGroupList(
            [
                new ContentType\ContentTypeGroup(),
                new ContentType\ContentTypeGroup(),
            ]
        );

        $this->getVisitorMock()->expects($this->exactly(2))
            ->method('visitValueObject')
            ->with($this->isInstanceOf(ContentTypeGroup::class));

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $contentTypeGroupList
        );
    }

    /**
     * Get the ContentTypeGroupList visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\ContentTypeGroupList
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\ContentTypeGroupList();
    }
}

class_alias(ContentTypeGroupListTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\ContentTypeGroupListTest');
