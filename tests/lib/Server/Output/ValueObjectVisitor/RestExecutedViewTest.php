<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\ContentService;
use Ibexa\Contracts\Core\Repository\ContentTypeService;
use Ibexa\Contracts\Core\Repository\LocationService;
use Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo;
use Ibexa\Contracts\Core\Repository\Values\Content\Search\SearchHit;
use Ibexa\Contracts\Core\Repository\Values\Content\Search\SearchResult;
use Ibexa\Core\Repository\Values\Content;
use Ibexa\Core\Repository\Values\Content as ApiValues;
use Ibexa\Core\Repository\Values\ContentType\ContentType;
use Ibexa\Rest\Server\Output\ValueObjectVisitor;
use Ibexa\Rest\Server\Values\RestExecutedView;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;

class RestExecutedViewTest extends ValueObjectVisitorBaseTest
{
    private const EXAMPLE_LOCATION_ID = 54;

    /**
     * Test the RestExecutedView visitor.
     *
     * @return \DOMDocument
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $view = new RestExecutedView(
            [
                'identifier' => 'test_view',
                'searchResults' => new SearchResult([
                    'searchHits' => [
                        $this->buildContentSearchHit(),
                        $this->buildLocationSearchHit(),
                    ],
                ]),
            ]
        );

        $this->addRouteExpectation(
            'ezpublish_rest_views_load',
            ['viewId' => $view->identifier],
            "/content/views/{$view->identifier}"
        );
        $this->addRouteExpectation(
            'ezpublish_rest_views_load_results',
            ['viewId' => $view->identifier],
            "/content/views/{$view->identifier}/results"
        );

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $view
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        $dom = new \DOMDocument();
        $dom->loadXml($result);

        return $dom;
    }

    public function provideXpathAssertions()
    {
        return [
            ['/View'],
            ['/View[@media-type="application/vnd.ez.api.View+xml"]'],
            ['/View[@href="/content/views/test_view"]'],
            ['/View/identifier'],
            ['/View/identifier[text()="test_view"]'],
            ['/View/Query'],
            ['/View/Query[@media-type="application/vnd.ez.api.Query+xml"]'],
            ['/View/Result'],
            ['/View/Result[@media-type="application/vnd.ez.api.ViewResult+xml"]'],
            ['/View/Result[@href="/content/views/test_view/results"]'],
            ['/View/Result/searchHits/searchHit[@score="0.123" and @index="alexandria"]'],
            ['/View/Result/searchHits/searchHit[@score="0.234" and @index="waze"]'],
        ];
    }

    /**
     * @param string $xpath
     * @param \DOMDocument $dom
     *
     * @depends testVisit
     * @dataProvider provideXpathAssertions
     */
    public function testGeneratedXml($xpath, \DOMDocument $dom)
    {
        $this->assertXPath($dom, $xpath);
    }

    /**
     * Get the Relation visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\RestExecutedView
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\RestExecutedView(
            $this->getLocationServiceMock(),
            $this->getContentServiceMock(),
        );
    }

    /**
     * @return \Ibexa\Contracts\Core\Repository\LocationService|\PHPUnit\Framework\MockObject\MockObject
     */
    public function getLocationServiceMock()
    {
        return $this->createMock(LocationService::class);
    }

    /**
     * @return \Ibexa\Contracts\Core\Repository\ContentService|\PHPUnit\Framework\MockObject\MockObject
     */
    public function getContentServiceMock()
    {
        $contentService = $this->createMock(ContentService::class);
        $contentService->method('loadRelations')->willReturn([]);

        return $contentService;
    }

    /**
     * @return \Ibexa\Contracts\Core\Repository\ContentTypeService|\PHPUnit\Framework\MockObject\MockObject
     */
    public function getContentTypeServiceMock()
    {
        return $this->createMock(ContentTypeService::class);
    }

    /**
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Search\SearchHit
     */
    protected function buildContentSearchHit()
    {
        return new SearchHit([
            'score' => 0.123,
            'index' => 'alexandria',
            'valueObject' => new ApiValues\Content([
                'versionInfo' => new Content\VersionInfo([
                    'contentInfo' => new ContentInfo([
                        'mainLocationId' => self::EXAMPLE_LOCATION_ID,
                    ]),
                ]),
                'contentType' => new ContentType(),
            ]),
        ]);
    }

    /**
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Search\SearchHit
     */
    protected function buildLocationSearchHit()
    {
        return new SearchHit([
            'score' => 0.234,
            'index' => 'waze',
            'valueObject' => new ApiValues\Location(['id' => 10]),
        ]);
    }
}

class_alias(RestExecutedViewTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\RestExecutedViewTest');
