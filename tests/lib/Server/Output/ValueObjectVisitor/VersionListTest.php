<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Tests\Output\ValueObjectVisitorBaseTest;
use EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Server\Values\VersionList;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\Core\Repository\Values\Content\VersionInfo;

class VersionListTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the VersionList visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $versionInfo = new VersionInfo(
            [
                'versionNo' => 1,
                'contentInfo' => new ContentInfo(['id' => 12345]),
            ]
        );
        $versionList = new VersionList([$versionInfo], '/some/path');

        $this->addRouteExpectation(
            'ezpublish_rest_loadContentInVersion',
            [
                'contentId' => $versionInfo->contentInfo->id,
                'versionNumber' => $versionInfo->versionNo,
            ],
            "/content/objects/{$versionInfo->contentInfo->id}/versions/{$versionInfo->versionNo}"
        );

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $versionList
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        return $result;
    }

    /**
     * Test if result contains VersionList element.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsVersionListElement($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'VersionList',
            ],
            $result,
            'Invalid <VersionList> element.',
            false
        );
    }

    /**
     * Test if result contains VersionList element attributes.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsVersionListAttributes($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'VersionList',
                'attributes' => [
                    'media-type' => 'application/vnd.ez.api.VersionList+xml',
                    'href' => '/some/path',
                ],
            ],
            $result,
            'Invalid <VersionList> attributes.',
            false
        );
    }

    /**
     * Test if VersionList visitor visits the children.
     */
    public function testVersionListVisitsChildren()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $versionList = new VersionList(
            [
                new VersionInfo(
                    [
                        'contentInfo' => new ContentInfo(
                            [
                                'id' => 42,
                            ]
                        ),
                        'versionNo' => 1,
                    ]
                ),
                new VersionInfo(
                    [
                        'contentInfo' => new ContentInfo(
                            [
                                'id' => 42,
                            ]
                        ),
                        'versionNo' => 2,
                    ]
                ),
            ],
            '/some/path'
        );

        $this->getVisitorMock()->expects($this->exactly(2))
            ->method('visitValueObject')
            ->with($this->isInstanceOf(\eZ\Publish\API\Repository\Values\Content\VersionInfo::class));

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $versionList
        );
    }

    /**
     * Get the VersionList visitor.
     *
     * @return \EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\VersionList
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\VersionList();
    }
}
