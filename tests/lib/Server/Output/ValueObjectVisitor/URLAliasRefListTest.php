<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\Content\URLAlias;
use Ibexa\Rest\Server\Output\ValueObjectVisitor;
use Ibexa\Rest\Server\Values\URLAliasRefList;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;

class URLAliasRefListTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the URLAliasRefList visitor.
     *
     * @return \DOMDocument
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $urlAliasRefList = new URLAliasRefList(
            [
                new URLAlias(
                    [
                        'id' => 'some-id',
                    ]
                ),
            ],
            '/some/path'
        );

        $this->addRouteExpectation(
            'ezpublish_rest_loadURLAlias',
            ['urlAliasId' => $urlAliasRefList->urlAliases[0]->id],
            "/content/urlaliases/{$urlAliasRefList->urlAliases[0]->id}"
        );

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $urlAliasRefList
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        $dom = new \DOMDocument();
        $dom->loadXml($result);

        return $dom;
    }

    /**
     * @param \DOMDocument $dom
     *
     * @depends testVisit
     */
    public function testUrlAliasRefListHrefCorrect(\DOMDocument $dom)
    {
        $this->assertXPath($dom, '/UrlAliasRefList[@href="/some/path"]');
    }

    /**
     * @param \DOMDocument $dom
     *
     * @depends testVisit
     */
    public function testUrlAliasRefListMediaTypeCorrect(\DOMDocument $dom)
    {
        $this->assertXPath($dom, '/UrlAliasRefList[@media-type="application/vnd.ez.api.UrlAliasRefList+xml"]');
    }

    /**
     * @param \DOMDocument $dom
     *
     * @depends testVisit
     */
    public function testUrlAliasHrefCorrect(\DOMDocument $dom)
    {
        $this->assertXPath($dom, '/UrlAliasRefList/UrlAlias[@href="/content/urlaliases/some-id"]');
    }

    /**
     * @param \DOMDocument $dom
     *
     * @depends testVisit
     */
    public function testUrlAliasMediaTypeCorrect(\DOMDocument $dom)
    {
        $this->assertXPath($dom, '/UrlAliasRefList/UrlAlias[@media-type="application/vnd.ez.api.UrlAlias+xml"]');
    }

    /**
     * Get the URLAliasRefList visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\URLAliasRefList
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\URLAliasRefList();
    }
}

class_alias(URLAliasRefListTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\URLAliasRefListTest');
