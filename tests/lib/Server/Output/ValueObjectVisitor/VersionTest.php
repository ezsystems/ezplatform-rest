<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo;
use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Core\Repository\Values;
use Ibexa\Rest\Output\FieldTypeSerializer;
use Ibexa\Rest\Server\Output\ValueObjectVisitor;
use Ibexa\Rest\Server\Values\Version;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;

class VersionTest extends ValueObjectVisitorBaseTest
{
    protected $fieldTypeSerializerMock;

    public function setUp(): void
    {
        $this->fieldTypeSerializerMock = $this->createMock(FieldTypeSerializer::class);
    }

    /**
     * Test the Version visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $version = new Version(
            new Values\Content\Content(
                [
                    'versionInfo' => new Values\Content\VersionInfo(
                        [
                            'versionNo' => 5,
                            'contentInfo' => new ContentInfo(
                                [
                                    'id' => 23,
                                    'contentTypeId' => 42,
                                ]
                            ),
                        ]
                    ),
                    'internalFields' => [
                        new Field(
                            [
                                'id' => 1,
                                'languageCode' => 'eng-US',
                                'fieldDefIdentifier' => 'ezauthor',
                                'fieldTypeIdentifier' => 'ezauthor',
                            ]
                        ),
                        new Field(
                            [
                                'id' => 2,
                                'languageCode' => 'eng-US',
                                'fieldDefIdentifier' => 'ezimage',
                                'fieldTypeIdentifier' => 'ezauthor',
                            ]
                        ),
                    ],
                ]
            ),
            $this->getMockForAbstractClass(ContentType::class),
            []
        );

        $this->fieldTypeSerializerMock->expects($this->exactly(2))
            ->method('serializeFieldValue')
            ->with(
                $this->isInstanceOf(Generator::class),
                $this->isInstanceOf(ContentType::class),
                $this->isInstanceOf(Field::class)
            );

        $this->getVisitorMock()->expects($this->exactly(2))
            ->method('visitValueObject');

        $this->addRouteExpectation(
            'ezpublish_rest_loadContentInVersion',
            [
                'contentId' => $version->content->id,
                'versionNumber' => $version->content->versionInfo->versionNo,
            ],
            "/content/objects/{$version->content->id}/versions/{$version->content->versionInfo->versionNo}"
        );

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $version
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        return $result;
    }

    /**
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsVersionChildren($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'Version',
                'children' => [
                    'less_than' => 2,
                    'greater_than' => 0,
                ],
            ],
            $result,
            'Invalid <Version> element.',
            false
        );
    }

    /**
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultVersionAttributes($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'Version',
                'attributes' => [
                    'media-type' => 'application/vnd.ez.api.Version+xml',
                    'href' => '/content/objects/23/versions/5',
                ],
            ],
            $result,
            'Invalid <Version> attributes.',
            false
        );
    }

    /**
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsFieldsChildren($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'Fields',
                'children' => [
                    'less_than' => 3,
                    'greater_than' => 1,
                ],
            ],
            $result,
            'Invalid <Fields> element.',
            false
        );
    }

    /**
     * Get the Version visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\Version
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\Version($this->fieldTypeSerializerMock);
    }
}

class_alias(VersionTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\VersionTest');
