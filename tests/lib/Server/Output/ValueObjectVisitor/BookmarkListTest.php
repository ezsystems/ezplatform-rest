<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use DOMDocument;
use DOMXPath;
use Ibexa\Contracts\Core\Repository\Values\Content\Location;
use Ibexa\Rest\Server\Output\ValueObjectVisitor;
use Ibexa\Rest\Server\Values\BookmarkList;
use Ibexa\Rest\Server\Values\RestLocation;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;

class BookmarkListTest extends ValueObjectVisitorBaseTest
{
    /**
     * @var \Ibexa\Rest\Server\Values\BookmarkList
     */
    private $data;

    protected function setUp(): void
    {
        $this->data = new BookmarkList(10, [
            new RestLocation($this->createMock(Location::class), 0),
            new RestLocation($this->createMock(Location::class), 0),
            new RestLocation($this->createMock(Location::class), 0),
        ]);
    }

    public function testVisit(): string
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $this->data
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        return $result;
    }

    /**
     * @depends testVisit
     */
    public function testResultContainsBookmarkListElement(string $result): void
    {
        $this->assertXMLTag(
            [
                'tag' => 'BookmarkList',
                'attributes' => [
                    'media-type' => 'application/vnd.ez.api.BookmarkList+xml',
                ],
            ],
            $result,
            'Invalid <BookmarkList> attributes.'
        );
    }

    /**
     * @depends testVisit
     */
    public function testResultContainsCountElement(string $result): void
    {
        $this->assertXMLTag(
            [
                'tag' => 'count',
                'content' => $this->data->totalCount,
            ],
            $result
        );
    }

    /**
     * @depends testVisit
     */
    public function testResultContainsBookmarkElement(string $result): void
    {
        $query = "//BookmarkList/Bookmark[@media-type='application/vnd.ez.api.Bookmark+xml']";

        $document = new DOMDocument();
        $document->loadXML($result);
        $xpath = new DOMXPath($document);

        $this->assertEquals(count($this->data->items), $xpath->query($query)->length);
    }

    /**
     * {@inheritdoc}
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\BookmarkList();
    }
}

class_alias(BookmarkListTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\BookmarkListTest');
