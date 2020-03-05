<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use eZ\Publish\Core\Repository\ContentService;
use eZ\Publish\Core\Repository\UserService;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\FieldTypeService;
use EzSystems\EzPlatformRest\Server\Input\Parser\UserUpdate;
use eZ\Publish\API\Repository\Values\Content\ContentMetadataUpdateStruct;
use eZ\Publish\API\Repository\Values\User\UserUpdateStruct;
use eZ\Publish\Core\Repository\Values\Content\ContentUpdateStruct;
use EzSystems\EzPlatformRest\Input\FieldTypeParser;
use EzSystems\EzPlatformRest\Server\Values\RestUserUpdateStruct;
use EzSystems\EzPlatformRest\Exceptions\Parser;

class UserUpdateTest extends BaseTest
{
    /**
     * Tests the UserUpdate parser.
     */
    public function testParse()
    {
        $inputArray = [
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/1',
            ],
            'remoteId' => 'remoteId123456',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'first_name',
                        'fieldValue' => [],
                    ],
                ],
            ],
            'email' => 'nospam@ez.no',
            'password' => 'somePassword',
            'enabled' => 'true',
            '__url' => '/user/users/14',
        ];

        $userUpdate = $this->getParser();
        $result = $userUpdate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            RestUserUpdateStruct::class,
            $result,
            'UserUpdate not created correctly.'
        );

        $this->assertInstanceOf(
            ContentUpdateStruct::class,
            $result->userUpdateStruct->contentUpdateStruct,
            'UserUpdate not created correctly.'
        );

        $this->assertInstanceOf(
            ContentMetadataUpdateStruct::class,
            $result->userUpdateStruct->contentMetadataUpdateStruct,
            'UserUpdate not created correctly.'
        );

        $this->assertEquals(
            1,
            $result->sectionId,
            'sectionId not created correctly'
        );

        $this->assertEquals(
            'eng-US',
            $result->userUpdateStruct->contentMetadataUpdateStruct->mainLanguageCode,
            'mainLanguageCode not created correctly'
        );

        $this->assertEquals(
            'remoteId123456',
            $result->userUpdateStruct->contentMetadataUpdateStruct->remoteId,
            'remoteId not created correctly'
        );

        $this->assertEquals(
            'nospam@ez.no',
            $result->userUpdateStruct->email,
            'email not created correctly'
        );

        $this->assertEquals(
            'somePassword',
            $result->userUpdateStruct->password,
            'password not created correctly'
        );

        $this->assertTrue(
            $result->userUpdateStruct->enabled,
            'enabled not created correctly'
        );

        foreach ($result->userUpdateStruct->contentUpdateStruct->fields as $field) {
            $this->assertEquals(
                'foo',
                $field->value,
                'field value not created correctly'
            );
        }
    }

    /**
     * Test UserUpdate parser throwing exception on missing Section href.
     */
    public function testParseExceptionOnMissingSectionHref()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'_href\' attribute for the Section element in UserUpdate.');
        $inputArray = [
            'mainLanguageCode' => 'eng-US',
            'Section' => [],
            'remoteId' => 'remoteId123456',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'first_name',
                        'fieldValue' => [],
                    ],
                ],
            ],
            'email' => 'nospam@ez.no',
            'password' => 'somePassword',
            'enabled' => 'true',
            '__url' => '/user/users/14',
        ];

        $userUpdate = $this->getParser();
        $userUpdate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserUpdate parser throwing exception on invalid fields data.
     */
    public function testParseExceptionOnInvalidFields()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Invalid \'fields\' element for UserUpdate.');
        $inputArray = [
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/1',
            ],
            'remoteId' => 'remoteId123456',
            'fields' => [],
            'email' => 'nospam@ez.no',
            'password' => 'somePassword',
            'enabled' => 'true',
            '__url' => '/user/users/14',
        ];

        $userUpdate = $this->getParser();
        $userUpdate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserUpdate parser throwing exception on missing field definition identifier.
     */
    public function testParseExceptionOnMissingFieldDefinitionIdentifier()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'fieldDefinitionIdentifier\' element in field data for UserUpdate.');
        $inputArray = [
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/1',
            ],
            'remoteId' => 'remoteId123456',
            'fields' => [
                'field' => [
                    [
                        'fieldValue' => [],
                    ],
                ],
            ],
            'email' => 'nospam@ez.no',
            'password' => 'somePassword',
            'enabled' => 'true',
            '__url' => '/user/users/14',
        ];

        $userUpdate = $this->getParser();
        $userUpdate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserUpdate parser throwing exception on missing field value.
     */
    public function testParseExceptionOnMissingFieldValue()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'fieldValue\' element for the \'first_name\' identifier in UserUpdate.');
        $inputArray = [
            'mainLanguageCode' => 'eng-US',
            'Section' => [
                '_href' => '/content/sections/1',
            ],
            'remoteId' => 'remoteId123456',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'first_name',
                    ],
                ],
            ],
            'email' => 'nospam@ez.no',
            'password' => 'somePassword',
            'enabled' => 'true',
            '__url' => '/user/users/14',
        ];

        $userUpdate = $this->getParser();
        $userUpdate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the UserUpdate parser.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\UserUpdate
     */
    protected function internalGetParser()
    {
        return new UserUpdate(
            $this->getUserServiceMock(),
            $this->getContentServiceMock(),
            $this->getFieldTypeParserMock(),
            $this->getParserTools()
        );
    }

    /**
     * Get the field type parser mock object.
     *
     * @return \EzSystems\EzPlatformRest\Input\FieldTypeParser ;
     */
    private function getFieldTypeParserMock()
    {
        $fieldTypeParserMock = $this->getMockBuilder(FieldTypeParser::class)
            ->setMethods([])
            ->disableOriginalConstructor()
            ->setConstructorArgs(
                [
                    $this->getContentServiceMock(),
                    $this->createMock(ContentTypeService::class),
                    $this->createMock(FieldTypeService::class),
                ]
            )
            ->getMock();

        $fieldTypeParserMock->expects($this->any())
            ->method('parseFieldValue')
            ->with(14, 'first_name', [])
            ->willReturn('foo');

        return $fieldTypeParserMock;
    }

    /**
     * Get the user service mock object.
     *
     * @return \eZ\Publish\API\Repository\UserService
     */
    protected function getUserServiceMock()
    {
        $userServiceMock = $this->createMock(UserService::class);

        $userServiceMock->expects($this->any())
            ->method('newUserUpdateStruct')
            ->willReturn(
                new UserUpdateStruct()
            );

        return $userServiceMock;
    }

    /**
     * Get the Content service mock object.
     *
     * @return \eZ\Publish\API\Repository\ContentService
     */
    protected function getContentServiceMock()
    {
        $contentServiceMock = $this->createMock(ContentService::class);

        $contentServiceMock->expects($this->any())
            ->method('newContentUpdateStruct')
            ->willReturn(
                new ContentUpdateStruct()
            );

        $contentServiceMock->expects($this->any())
            ->method('newContentMetadataUpdateStruct')
            ->willReturn(
                new ContentMetadataUpdateStruct()
            );

        return $contentServiceMock;
    }

    public function getParseHrefExpectationsMap()
    {
        return [
            ['/user/users/14', 'userId', 14],
            ['/content/sections/1', 'sectionId', 1],
        ];
    }
}
