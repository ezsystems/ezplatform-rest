<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\ContentService;
use Ibexa\Contracts\Core\Repository\FieldTypeService;
use Ibexa\Contracts\Rest\Exceptions\Parser;
use Ibexa\Core\Repository\ContentTypeService;
use Ibexa\Core\Repository\UserService;
use Ibexa\Core\Repository\Values\ContentType\ContentType;
use Ibexa\Core\Repository\Values\ContentType\FieldDefinition;
use Ibexa\Core\Repository\Values\ContentType\FieldDefinitionCollection;
use Ibexa\Core\Repository\Values\User\UserGroupCreateStruct;
use Ibexa\Rest\Input\FieldTypeParser;
use Ibexa\Rest\Server\Input\Parser\UserGroupCreate;

class UserGroupCreateTest extends BaseTest
{
    /**
     * Tests the UserGroupCreate parser.
     */
    public function testParse()
    {
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/3',
            ],
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'remoteId' => 'remoteId12345678',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'name',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $userGroupCreate = $this->getParser();
        $result = $userGroupCreate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            UserGroupCreateStruct::class,
            $result,
            'UserGroupCreateStruct not created correctly.'
        );

        $this->assertInstanceOf(
            \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType::class,
            $result->contentType,
            'contentType not created correctly.'
        );

        $this->assertEquals(
            3,
            $result->contentType->id,
            'contentType not created correctly'
        );

        $this->assertEquals(
            'eng-US',
            $result->mainLanguageCode,
            'mainLanguageCode not created correctly'
        );

        $this->assertEquals(
            4,
            $result->sectionId,
            'sectionId not created correctly'
        );

        $this->assertEquals(
            'remoteId12345678',
            $result->remoteId,
            'remoteId not created correctly'
        );

        foreach ($result->fields as $field) {
            $this->assertEquals(
                'foo',
                $field->value,
                'field value not created correctly'
            );
        }
    }

    /**
     * Test UserGroupCreate parser throwing exception on invalid ContentType.
     */
    public function testParseExceptionOnInvalidContentType()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'_href\' attribute for the ContentType element in UserGroupCreate.');
        $inputArray = [
            'ContentType' => [],
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'remoteId' => 'remoteId12345678',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'name',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $userGroupCreate = $this->getParser();
        $userGroupCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserGroupCreate parser throwing exception on missing mainLanguageCode.
     */
    public function testParseExceptionOnMissingMainLanguageCode()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'mainLanguageCode\' element for UserGroupCreate.');
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/3',
            ],
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'remoteId' => 'remoteId12345678',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'name',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $userGroupCreate = $this->getParser();
        $userGroupCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserGroupCreate parser throwing exception on invalid Section.
     */
    public function testParseExceptionOnInvalidSection()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'_href\' attribute for the Section element in UserGroupCreate.');
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/3',
            ],
            'mainLanguageCode' => 'eng-US',
            'Section' => [],
            'remoteId' => 'remoteId12345678',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'name',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $userGroupCreate = $this->getParser();
        $userGroupCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserGroupCreate parser throwing exception on invalid fields data.
     */
    public function testParseExceptionOnInvalidFields()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing or invalid \'fields\' element for UserGroupCreate.');
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/3',
            ],
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'remoteId' => 'remoteId12345678',
        ];

        $userGroupCreate = $this->getParser();
        $userGroupCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserGroupCreate parser throwing exception on missing field definition identifier.
     */
    public function testParseExceptionOnMissingFieldDefinitionIdentifier()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'fieldDefinitionIdentifier\' element in field data for UserGroupCreate.');
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/3',
            ],
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'remoteId' => 'remoteId12345678',
            'fields' => [
                'field' => [
                    [
                        'fieldValue' => [],
                    ],
                    [
                        'fieldDefinitionIdentifier' => 'name',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $userGroupCreate = $this->getParser();
        $userGroupCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserGroupCreate parser throwing exception on invalid field definition identifier.
     */
    public function testParseExceptionOnInvalidFieldDefinitionIdentifier()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('\'unknown\' is an invalid Field definition identifier for the \'some_class\' Content Type in UserGroupCreate.');
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/3',
            ],
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'remoteId' => 'remoteId12345678',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'unknown',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $userGroupCreate = $this->getParser();
        $userGroupCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserGroupCreate parser throwing exception on missing field value.
     */
    public function testParseExceptionOnMissingFieldValue()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'fieldValue\' element for the \'name\' identifier in UserGroupCreate.');
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/3',
            ],
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'remoteId' => 'remoteId12345678',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'name',
                    ],
                ],
            ],
        ];

        $userGroupCreate = $this->getParser();
        $userGroupCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the UserGroupCreate parser.
     *
     * @return \Ibexa\Rest\Server\Input\Parser\UserGroupCreate
     */
    protected function internalGetParser()
    {
        return new UserGroupCreate(
            $this->getUserServiceMock(),
            $this->getContentTypeServiceMock(),
            $this->getFieldTypeParserMock()
        );
    }

    /**
     * Get the field type parser mock object.
     *
     * @return \Ibexa\Rest\Input\FieldTypeParser ;
     */
    private function getFieldTypeParserMock()
    {
        $fieldTypeParserMock = $this->getMockBuilder(FieldTypeParser::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->setConstructorArgs(
                [
                    $this->createMock(ContentService::class),
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
     * Get the user service mock object.
     *
     * @return \Ibexa\Contracts\Core\Repository\UserService
     */
    protected function getUserServiceMock()
    {
        $userServiceMock = $this->createMock(UserService::class);

        $contentType = $this->getContentType();
        $userServiceMock->expects($this->any())
            ->method('newUserGroupCreateStruct')
            ->with(
                $this->equalTo('eng-US'),
                $this->equalTo($contentType)
            )
            ->willReturn(
                new UserGroupCreateStruct(
                    [
                            'contentType' => $contentType,
                            'mainLanguageCode' => 'eng-US',
                        ]
                )
            );

        return $userServiceMock;
    }

    /**
     * Get the content type service mock object.
     *
     * @return \Ibexa\Contracts\Core\Repository\ContentTypeService
     */
    protected function getContentTypeServiceMock()
    {
        $contentTypeServiceMock = $this->createMock(ContentTypeService::class);

        $contentTypeServiceMock->expects($this->any())
            ->method('loadContentType')
            ->with($this->equalTo(3))
            ->willReturn($this->getContentType());

        return $contentTypeServiceMock;
    }

    /**
     * Get the content type used in UserGroupCreate parser.
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType
     */
    protected function getContentType()
    {
        return new ContentType(
            [
                'id' => 3,
                'identifier' => 'some_class',
                'fieldDefinitions' => new FieldDefinitionCollection([
                    new FieldDefinition(
                        [
                            'id' => 42,
                            'identifier' => 'name',
                            'fieldTypeIdentifier' => 'ezstring',
                        ]
                    ),
                ]),
            ]
        );
    }

    public function getParseHrefExpectationsMap()
    {
        return [
            ['/content/types/3', 'contentTypeId', 3],
            ['/content/sections/4', 'sectionId', 4],
        ];
    }
}

class_alias(UserGroupCreateTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\UserGroupCreateTest');
