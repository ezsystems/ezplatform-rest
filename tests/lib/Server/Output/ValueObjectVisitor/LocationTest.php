<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Location as ApiLocation;
use eZ\Publish\Core\Repository\Values\Content\Content;
use eZ\Publish\Core\Repository\Values\Content\Location;
use eZ\Publish\Core\Repository\Values\Content\VersionInfo;
use eZ\Publish\Core\Repository\Values\ContentType\ContentType;
use EzSystems\EzPlatformRest\Tests\Output\ValueObjectVisitorBaseTest;
use EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

final class LocationTest extends ValueObjectVisitorBaseTest
{
    /** @var \eZ\Publish\API\Repository\LocationService|\PHPUnit\Framework\MockObject\MockObject */
    private $locationServiceMock;

    /** @var \eZ\Publish\API\Repository\ContentService|\PHPUnit\Framework\MockObject\MockObject */
    private $contentServiceMock;

    protected function setUp(): void
    {
        $this->locationServiceMock = $this->createMock(LocationService::class);
        $this->contentServiceMock = $this->createMock(ContentService::class);

        parent::setUp();
    }

    public function testVisitWithDifferentLocationToMainLocation(): string
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $location = new Location([
            'id' => 55,
            'path' => ['1', '25', '42'],
            'priority' => 1,
            'sortField' => ApiLocation::SORT_FIELD_DEPTH,
            'sortOrder' => ApiLocation::SORT_ORDER_ASC,
            'parentLocationId' => 42,
            'contentInfo' => new ContentInfo([
                'id' => $contentId = 7,
                'mainLocationId' => $mainLocationId = 78,
            ]),
            'content' => new Content([
                'id' => $contentId,
                'contentType' => new ContentType(),
                'versionInfo' => $versionInfo = new VersionInfo(),
            ])
        ]);

        $this->locationServiceMock->expects(self::once())
            ->method('loadLocation')
            ->with($mainLocationId)
            ->willReturn(new Location(['id' => $mainLocationId]));

        $this->contentServiceMock->expects(self::once())
            ->method('loadRelations')
            ->with($versionInfo)
            ->willReturn([]);

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $location
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        return $result;
    }

    /**
     * @depends testVisitWithDifferentLocationToMainLocation
     */
    public function testResultContainsLocationListElement(string $result): void
    {
        $this->assertXMLTag(
            [
                'tag' => 'Location',
            ],
            $result,
            'Invalid <Location> element.',
        );
    }

    /**
     * @depends testVisitWithDifferentLocationToMainLocation
     */
    public function testResultContainsLocationAttributes(string $result): void
    {
        $this->assertXMLTag(
            [
                'tag' => 'Location',
                'content' => 55 . 1 . 'false' . 'false',
            ],
            $result,
            'Invalid <Location> attributes.',
        );
    }

    protected function internalGetVisitor(): ValueObjectVisitor\Location
    {
        return new ValueObjectVisitor\Location($this->locationServiceMock, $this->contentServiceMock);
    }
}
