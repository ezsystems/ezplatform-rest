<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use eZ\Publish\API\Repository\Values\Content\LocationCreateStruct;
use eZ\Publish\Core\Repository\ContentService;
use eZ\Publish\Core\Repository\ContentTypeService;
use eZ\Publish\Core\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\Core\Repository\Values\Content\ContentCreateStruct;
use eZ\Publish\Core\Repository\Values\ContentType\ContentType;
use EzSystems\EzPlatformRest\Server\Input\Parser\ContentCreate;
use EzSystems\EzPlatformRest\Server\Input\Parser\LocationCreate;
use EzSystems\EzPlatformRest\Input\FieldTypeParser;
use eZ\Publish\API\Repository\FieldTypeService;

class ContentCreateTest extends BaseTest
{
    /**
     * Tests the ContentCreate parser.
     */
    public function testParse()
    {
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/13',
            ],
            'mainLanguageCode' => 'eng-US',
            'LocationCreate' => [],
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'alwaysAvailable' => 'true',
            'remoteId' => 'remoteId12345678',
            'User' => [
                '_href' => '/user/users/14',
            ],
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'subject',
                        'fieldValue' => [],
                    ],
                    [
                        'fieldDefinitionIdentifier' => 'author',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $contentCreate = $this->getParser();
        $result = $contentCreate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            '\\EzSystems\\EzPlatformRest\\Server\\Values\\RestContentCreateStruct',
            $result,
            'ContentCreate not created correctly.'
        );

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\ContentCreateStruct',
            $result->contentCreateStruct,
            'contentCreateStruct not created correctly.'
        );

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\ContentType\\ContentType',
            $result->contentCreateStruct->contentType,
            'contentType not created correctly.'
        );

        $this->assertEquals(
            13,
            $result->contentCreateStruct->contentType->id,
            'contentType not created correctly'
        );

        $this->assertEquals(
            'eng-US',
            $result->contentCreateStruct->mainLanguageCode,
            'mainLanguageCode not created correctly'
        );

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\Content\\LocationCreateStruct',
            $result->locationCreateStruct,
            'locationCreateStruct not created correctly.'
        );

        $this->assertEquals(
            4,
            $result->contentCreateStruct->sectionId,
            'sectionId not created correctly'
        );

        $this->assertTrue(
            $result->contentCreateStruct->alwaysAvailable,
            'alwaysAvailable not created correctly'
        );

        $this->assertEquals(
            'remoteId12345678',
            $result->contentCreateStruct->remoteId,
            'remoteId not created correctly'
        );

        $this->assertEquals(
            14,
            $result->contentCreateStruct->ownerId,
            'ownerId not created correctly'
        );

        foreach ($result->contentCreateStruct->fields as $field) {
            $this->assertEquals(
                'foo',
                $field->value,
                'field value not created correctly'
            );
        }
    }

    /**
     * Test ContentCreate parser throwing exception on missing LocationCreate.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Missing or invalid 'LocationCreate' element for ContentCreate.
     */
    public function testParseExceptionOnMissingLocationCreate()
    {
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/13',
            ],
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'alwaysAvailable' => 'true',
            'remoteId' => 'remoteId12345678',
            'User' => [
                '_href' => '/user/users/14',
            ],
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'subject',
                        'fieldValue' => [],
                    ],
                    [
                        'fieldDefinitionIdentifier' => 'author',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $contentCreate = $this->getParser();
        $contentCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ContentCreate parser throwing exception on missing ContentType.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Missing or invalid 'ContentType' element for ContentCreate.
     */
    public function testParseExceptionOnMissingContentType()
    {
        $inputArray = [
            'mainLanguageCode' => 'eng-US',
            'LocationCreate' => [],
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'alwaysAvailable' => 'true',
            'remoteId' => 'remoteId12345678',
            'User' => [
                '_href' => '/user/users/14',
            ],
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'subject',
                        'fieldValue' => [],
                    ],
                    [
                        'fieldDefinitionIdentifier' => 'author',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $contentCreate = $this->getParser();
        $contentCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ContentCreate parser throwing exception on invalid ContentType.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Missing '_href' attribute for the ContentType element in ContentCreate.
     */
    public function testParseExceptionOnInvalidContentType()
    {
        $inputArray = [
            'ContentType' => [],
            'mainLanguageCode' => 'eng-US',
            'LocationCreate' => [],
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'alwaysAvailable' => 'true',
            'remoteId' => 'remoteId12345678',
            'User' => [
                '_href' => '/user/users/14',
            ],
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'subject',
                        'fieldValue' => [],
                    ],
                    [
                        'fieldDefinitionIdentifier' => 'author',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $contentCreate = $this->getParser();
        $contentCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ContentCreate parser throwing exception on missing mainLanguageCode.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Missing 'mainLanguageCode' element for ContentCreate.
     */
    public function testParseExceptionOnMissingMainLanguageCode()
    {
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/13',
            ],
            'LocationCreate' => [],
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'alwaysAvailable' => 'true',
            'remoteId' => 'remoteId12345678',
            'User' => [
                '_href' => '/user/users/14',
            ],
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'subject',
                        'fieldValue' => [],
                    ],
                    [
                        'fieldDefinitionIdentifier' => 'author',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $contentCreate = $this->getParser();
        $contentCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ContentCreate parser throwing exception on invalid Section.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Missing '_href' attribute for the Section element in ContentCreate.
     */
    public function testParseExceptionOnInvalidSection()
    {
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/13',
            ],
            'mainLanguageCode' => 'eng-US',
            'LocationCreate' => [],
            'Section' => [],
            'alwaysAvailable' => 'true',
            'remoteId' => 'remoteId12345678',
            'User' => [
                '_href' => '/user/users/14',
            ],
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'subject',
                        'fieldValue' => [],
                    ],
                    [
                        'fieldDefinitionIdentifier' => 'author',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $contentCreate = $this->getParser();
        $contentCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ContentCreate parser throwing exception on invalid User.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Missing '_href' attribute for the User element in ContentCreate.
     */
    public function testParseExceptionOnInvalidUser()
    {
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/13',
            ],
            'mainLanguageCode' => 'eng-US',
            'LocationCreate' => [],
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'alwaysAvailable' => 'true',
            'remoteId' => 'remoteId12345678',
            'User' => [],
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'subject',
                        'fieldValue' => [],
                    ],
                    [
                        'fieldDefinitionIdentifier' => 'author',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $contentCreate = $this->getParser();
        $contentCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ContentCreate parser throwing exception on invalid fields data.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Missing or invalid 'fields' element for ContentCreate.
     */
    public function testParseExceptionOnInvalidFields()
    {
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/13',
            ],
            'mainLanguageCode' => 'eng-US',
            'LocationCreate' => [],
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'alwaysAvailable' => 'true',
            'remoteId' => 'remoteId12345678',
            'User' => [
                '_href' => '/user/users/14',
            ],
        ];

        $contentCreate = $this->getParser();
        $contentCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ContentCreate parser throwing exception on missing field definition identifier.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Missing 'fieldDefinitionIdentifier' element in Field data for ContentCreate.
     */
    public function testParseExceptionOnMissingFieldDefinitionIdentifier()
    {
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/13',
            ],
            'mainLanguageCode' => 'eng-US',
            'LocationCreate' => [],
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'alwaysAvailable' => 'true',
            'remoteId' => 'remoteId12345678',
            'User' => [
                '_href' => '/user/users/14',
            ],
            'fields' => [
                'field' => [
                    [
                        'fieldValue' => [],
                    ],
                    [
                        'fieldDefinitionIdentifier' => 'author',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $contentCreate = $this->getParser();
        $contentCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ContentCreate parser throwing exception on invalid field definition identifier.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage 'unknown' is an invalid Field definition identifier for the 'some_class' Content Type in ContentCreate.
     */
    public function testParseExceptionOnInvalidFieldDefinitionIdentifier()
    {
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/13',
            ],
            'mainLanguageCode' => 'eng-US',
            'LocationCreate' => [],
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'alwaysAvailable' => 'true',
            'remoteId' => 'remoteId12345678',
            'User' => [
                '_href' => '/user/users/14',
            ],
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'unknown',
                        'fieldValue' => [],
                    ],
                    [
                        'fieldDefinitionIdentifier' => 'author',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $contentCreate = $this->getParser();
        $contentCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ContentCreate parser throwing exception on missing field value.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Missing 'fieldValue' element for the 'subject' identifier in ContentCreate.
     */
    public function testParseExceptionOnMissingFieldValue()
    {
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/13',
            ],
            'mainLanguageCode' => 'eng-US',
            'LocationCreate' => [],
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'alwaysAvailable' => 'true',
            'remoteId' => 'remoteId12345678',
            'User' => [
                '_href' => '/user/users/14',
            ],
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'subject',
                    ],
                    [
                        'fieldDefinitionIdentifier' => 'author',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $contentCreate = $this->getParser();
        $contentCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the ContentCreate parser.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\ContentCreate
     */
    protected function internalGetParser()
    {
        return new ContentCreate(
            $this->getContentServiceMock(),
            $this->getContentTypeServiceMock(),
            $this->getFieldTypeParserMock(),
            $this->getLocationCreateParserMock(),
            $this->getParserTools()
        );
    }

    /**
     * Get the field type parser mock object.
     *
     * @return \EzSystems\EzPlatformRest\Input\FieldTypeParser;
     */
    private function getFieldTypeParserMock()
    {
        $fieldTypeParserMock = $this->getMockBuilder(FieldTypeParser::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->setConstructorArgs(
                [
                    $this->getContentServiceMock(),
                    $this->getContentTypeServiceMock(),
                    $this->createMock(FieldTypeService::class),
                ]
            )
            ->getMock();

        $fieldTypeParserMock->expects($this->any())
            ->method('parseValue')
            ->with('ezstring', [])
            ->willReturn('foo');

        return $fieldTypeParserMock;
    }

    /**
     * Returns the LocationCreate parser mock object.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\LocationCreate
     */
    private function getLocationCreateParserMock()
    {
        $locationCreateParserMock = $this->createMock(LocationCreate::class);

        $locationCreateParserMock->expects($this->any())
            ->method('parse')
            ->with([], $this->getParsingDispatcherMock())
            ->willReturn(new LocationCreateStruct());

        return $locationCreateParserMock;
    }

    /**
     * Get the content service mock object.
     *
     * @return \eZ\Publish\API\Repository\ContentService
     */
    protected function getContentServiceMock()
    {
        $contentServiceMock = $this->createMock(ContentService::class);

        $contentType = $this->getContentType();
        $contentServiceMock->expects($this->any())
            ->method('newContentCreateStruct')
            ->with(
                $this->equalTo($contentType),
                $this->equalTo('eng-US')
            )
            ->willReturn(
                    new ContentCreateStruct(
                        [
                            'contentType' => $contentType,
                            'mainLanguageCode' => 'eng-US',
                        ]
                    )
            );

        return $contentServiceMock;
    }

    /**
     * Get the content type service mock object.
     *
     * @return \eZ\Publish\API\Repository\ContentTypeService
     */
    protected function getContentTypeServiceMock()
    {
        $contentTypeServiceMock = $this->createMock(ContentTypeService::class);

        $contentTypeServiceMock->expects($this->any())
            ->method('loadContentType')
            ->with($this->equalTo(13))
            ->willReturn($this->getContentType());

        return $contentTypeServiceMock;
    }

    public function getParseHrefExpectationsMap()
    {
        return [
            ['/content/types/13', 'contentTypeId', 13],
            ['/content/sections/4', 'sectionId', 4],
            ['/user/users/14', 'userId', 14],
        ];
    }

    /**
     * Get the content type used in ContentCreate parser.
     *
     * @return \eZ\Publish\API\Repository\Values\ContentType\ContentType
     */
    protected function getContentType()
    {
        return new ContentType(
            [
                'id' => 13,
                'identifier' => 'some_class',
                'fieldDefinitions' => [
                    new FieldDefinition(
                        [
                            'id' => 42,
                            'identifier' => 'subject',
                            'fieldTypeIdentifier' => 'ezstring',
                        ]
                    ),
                    new FieldDefinition(
                        [
                            'id' => 43,
                            'identifier' => 'author',
                            'fieldTypeIdentifier' => 'ezstring',
                        ]
                    ),
                ],
            ]
        );
    }
}
