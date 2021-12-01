<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Core\Repository\Values\ContentType;
use Ibexa\Rest\Server\Output\ValueObjectVisitor;
use Ibexa\Rest\Server\Values\ContentTypeList;
use Ibexa\Rest\Server\Values\RestContentType;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;

class ContentTypeListTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the ContentTypeList visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $contentTypeList = new ContentTypeList([], '/content/typegroups/2/types');

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $contentTypeList
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        return $result;
    }

    /**
     * Test if result contains ContentTypeList element.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsContentTypeListElement($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'ContentTypeList',
            ],
            $result,
            'Invalid <ContentTypeList> element.',
            false
        );
    }

    /**
     * Test if result contains ContentTypeList element attributes.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsContentTypeListAttributes($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'ContentTypeList',
                'attributes' => [
                    'media-type' => 'application/vnd.ez.api.ContentTypeList+xml',
                    'href' => '/content/typegroups/2/types',
                ],
            ],
            $result,
            'Invalid <ContentTypeList> attributes.',
            false
        );
    }

    /**
     * Test if ContentTypeList visitor visits the children.
     */
    public function testContentTypeListVisitsChildren()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $contentTypeList = new ContentTypeList(
            [
                new ContentType\ContentType(
                    [
                        'fieldDefinitions' => new ContentType\FieldDefinitionCollection([]),
                    ]
                ),
                new ContentType\ContentType(
                    [
                        'fieldDefinitions' => new ContentType\FieldDefinitionCollection([]),
                    ]
                ),
            ],
            '/content/typegroups/2/types'
        );

        $this->getVisitorMock()->expects($this->exactly(2))
            ->method('visitValueObject')
            ->with($this->isInstanceOf(RestContentType::class));

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $contentTypeList
        );
    }

    /**
     * Get the ContentTypeList visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\ContentTypeList
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\ContentTypeList();
    }
}

class_alias(ContentTypeListTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\ContentTypeListTest');
