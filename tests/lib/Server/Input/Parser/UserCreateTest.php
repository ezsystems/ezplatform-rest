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
use Ibexa\Core\Repository\Values\User\UserCreateStruct;
use Ibexa\Rest\Input\FieldTypeParser;
use Ibexa\Rest\Server\Input\Parser\UserCreate;

class UserCreateTest extends BaseTest
{
    /**
     * Tests the UserCreate parser.
     */
    public function testParse()
    {
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/4',
            ],
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'remoteId' => 'remoteId12345678',
            'login' => 'login',
            'email' => 'admin@link.invalid',
            'password' => 'password',
            'enabled' => 'true',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'name',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $userCreate = $this->getParser();
        $result = $userCreate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            UserCreateStruct::class,
            $result,
            'UserCreateStruct not created correctly.'
        );

        $this->assertInstanceOf(
            ContentType::class,
            $result->contentType,
            'contentType not created correctly.'
        );

        $this->assertEquals(
            4,
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
     * Test UserCreate parser throwing exception on invalid ContentType.
     */
    public function testParseExceptionOnInvalidContentType()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'_href\' attribute for the ContentType element in UserCreate.');
        $inputArray = [
            'ContentType' => [],
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'remoteId' => 'remoteId12345678',
            'login' => 'login',
            'email' => 'admin@link.invalid',
            'password' => 'password',
            'enabled' => 'true',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'name',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $userCreate = $this->getParser();
        $userCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserCreate parser throwing exception on missing mainLanguageCode.
     */
    public function testParseExceptionOnMissingMainLanguageCode()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'mainLanguageCode\' element for UserCreate.');
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/4',
            ],
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'remoteId' => 'remoteId12345678',
            'login' => 'login',
            'email' => 'admin@link.invalid',
            'password' => 'password',
            'enabled' => 'true',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'name',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $userCreate = $this->getParser();
        $userCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserCreate parser throwing exception on missing login.
     */
    public function testParseExceptionOnMissingLogin()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'login\' element for UserCreate.');
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/4',
            ],
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'remoteId' => 'remoteId12345678',
            'email' => 'admin@link.invalid',
            'password' => 'password',
            'enabled' => 'true',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'name',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $userCreate = $this->getParser();
        $userCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserCreate parser throwing exception on missing email.
     */
    public function testParseExceptionOnMissingEmail()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'email\' element for UserCreate.');
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/4',
            ],
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'remoteId' => 'remoteId12345678',
            'login' => 'login',
            'password' => 'password',
            'enabled' => 'true',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'name',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $userCreate = $this->getParser();
        $userCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserCreate parser throwing exception on missing password.
     */
    public function testParseExceptionOnMissingPassword()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'password\' element for UserCreate.');
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/4',
            ],
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'remoteId' => 'remoteId12345678',
            'login' => 'login',
            'email' => 'admin@link.invalid',
            'enabled' => 'true',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'name',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $userCreate = $this->getParser();
        $userCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserCreate parser throwing exception on invalid Section.
     */
    public function testParseExceptionOnInvalidSection()
    {
        $this->expectException('EzSystems\EzPlatformRest\Exceptions\Parser');
        $this->expectExceptionMessage('Missing \'_href\' attribute for the Section element in UserCreate.');
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/4',
            ],
            'mainLanguageCode' => 'eng-US',
            'Section' => [],
            'remoteId' => 'remoteId12345678',
            'login' => 'login',
            'email' => 'admin@link.invalid',
            'password' => 'password',
            'enabled' => 'true',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'name',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $userCreate = $this->getParser();
        $userCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserCreate parser throwing exception on invalid fields data.
     */
    public function testParseExceptionOnInvalidFields()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing or invalid \'fields\' element for UserCreate.');
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/4',
            ],
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'remoteId' => 'remoteId12345678',
            'login' => 'login',
            'email' => 'admin@link.invalid',
            'password' => 'password',
            'enabled' => 'true',
        ];

        $userCreate = $this->getParser();
        $userCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserCreate parser throwing exception on missing field definition identifier.
     */
    public function testParseExceptionOnMissingFieldDefinitionIdentifier()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'fieldDefinitionIdentifier\' element in field data for UserCreate.');
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/4',
            ],
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'remoteId' => 'remoteId12345678',
            'login' => 'login',
            'email' => 'admin@link.invalid',
            'password' => 'password',
            'enabled' => 'true',
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

        $userCreate = $this->getParser();
        $userCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserCreate parser throwing exception on invalid field definition identifier.
     */
    public function testParseExceptionOnInvalidFieldDefinitionIdentifier()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('\'unknown\' is an invalid Field definition identifier for the \'some_class\' Content Type in UserCreate.');
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/4',
            ],
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'remoteId' => 'remoteId12345678',
            'login' => 'login',
            'email' => 'admin@link.invalid',
            'password' => 'password',
            'enabled' => 'true',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'unknown',
                        'fieldValue' => [],
                    ],
                ],
            ],
        ];

        $userCreate = $this->getParser();
        $userCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserCreate parser throwing exception on missing field value.
     */
    public function testParseExceptionOnMissingFieldValue()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'fieldValue\' element for the \'name\' identifier in UserCreate.');
        $inputArray = [
            'ContentType' => [
                '_href' => '/content/types/4',
            ],
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/4',
            ],
            'remoteId' => 'remoteId12345678',
            'login' => 'login',
            'email' => 'admin@link.invalid',
            'password' => 'password',
            'enabled' => 'true',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'name',
                    ],
                ],
            ],
        ];

        $userCreate = $this->getParser();
        $userCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the UserCreate parser.
     *
     * @return \Ibexa\Rest\Server\Input\Parser\UserCreate
     */
    protected function internalGetParser()
    {
        return new UserCreate(
            $this->getUserServiceMock(),
            $this->getContentTypeServiceMock(),
            $this->getFieldTypeParserMock(),
            $this->getParserTools()
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
            ->setMethods([])
            ->disableOriginalConstructor()
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
            ->method('newUserCreateStruct')
            ->with(
                $this->equalTo('login'),
                $this->equalTo('admin@link.invalid'),
                $this->equalTo('password'),
                $this->equalTo('eng-US'),
                $this->equalTo($contentType)
            )
            ->willReturn(
                new UserCreateStruct(
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
            ->with($this->equalTo(4))
            ->willReturn($this->getContentType());

        return $contentTypeServiceMock;
    }

    /**
     * Get the content type used in UserCreate parser.
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType
     */
    protected function getContentType()
    {
        return new ContentType(
            [
                'id' => 4,
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
            ['/content/types/4', 'contentTypeId', 4],
            ['/content/sections/4', 'sectionId', 4],
        ];
    }
}

class_alias(UserCreateTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\UserCreateTest');
