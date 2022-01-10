<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\Content;
use Ibexa\Rest\Server\Output\ValueObjectVisitor;
use Ibexa\Rest\Server\Values\URLWildcardList;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;

class URLWildcardListTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the URLWildcardList visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $urlWildcardList = new URLWildcardList([]);

        $this->addRouteExpectation(
            'ezpublish_rest_listURLWildcards',
            [],
            '/content/urlwildcards'
        );

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $urlWildcardList
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        return $result;
    }

    /**
     * Test if result contains UrlWildcardList element.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsUrlWildcardListElement($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'UrlWildcardList',
            ],
            $result,
            'Invalid <UrlWildcardList> element.',
            false
        );
    }

    /**
     * Test if result contains UrlWildcardList element attributes.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsUrlWildcardListAttributes($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'UrlWildcardList',
                'attributes' => [
                    'media-type' => 'application/vnd.ez.api.UrlWildcardList+xml',
                    'href' => '/content/urlwildcards',
                ],
            ],
            $result,
            'Invalid <UrlWildcardList> attributes.',
            false
        );
    }

    /**
     * Test if URLWildcardList visitor visits the children.
     */
    public function testURLWildcardListVisitsChildren()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $urlWildcardList = new URLWildcardList(
            [
                new Content\URLWildcard(),
                new Content\URLWildcard(),
            ]
        );

        $this->getVisitorMock()->expects($this->exactly(2))
            ->method('visitValueObject')
            ->with($this->isInstanceOf(Content\URLWildcard::class));

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $urlWildcardList
        );
    }

    /**
     * Get the URLWildcardList visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\URLWildcardList
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\URLWildcardList();
    }
}

class_alias(URLWildcardListTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\URLWildcardListTest');
