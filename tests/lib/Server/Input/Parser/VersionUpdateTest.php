<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\ContentTypeService;
use Ibexa\Contracts\Core\Repository\FieldTypeService;
use Ibexa\Contracts\Rest\Exceptions\Parser;
use Ibexa\Core\Repository\ContentService;
use Ibexa\Core\Repository\Values\Content\ContentUpdateStruct;
use Ibexa\Rest\Input\FieldTypeParser;
use Ibexa\Rest\Server\Input\Parser\VersionUpdate;

class VersionUpdateTest extends BaseTest
{
    /**
     * Tests the VersionUpdate parser.
     */
    public function testParse()
    {
        $inputArray = [
            'initialLanguageCode' => 'eng-US',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'subject',
                        'fieldValue' => [],
                    ],
                ],
            ],
            '__url' => '/content/objects/42/versions/1',
        ];

        $VersionUpdate = $this->getParser();
        $result = $VersionUpdate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            ContentUpdateStruct::class,
            $result,
            'VersionUpdate not created correctly.'
        );

        $this->assertEquals(
            'eng-US',
            $result->initialLanguageCode,
            'initialLanguageCode not created correctly'
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
     * Test VersionUpdate parser throwing exception on invalid fields data.
     */
    public function testParseExceptionOnInvalidFields()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Invalid \'fields\' element for VersionUpdate.');
        $inputArray = [
            'initialLanguageCode' => 'eng-US',
            'fields' => [],
            '__url' => '/content/objects/42/versions/1',
        ];

        $VersionUpdate = $this->getParser();
        $VersionUpdate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test VersionUpdate parser throwing exception on missing field definition identifier.
     */
    public function testParseExceptionOnMissingFieldDefinitionIdentifier()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'fieldDefinitionIdentifier\' element in Field data for VersionUpdate.');
        $inputArray = [
            'initialLanguageCode' => 'eng-US',
            'fields' => [
                'field' => [
                    [
                        'fieldValue' => [],
                    ],
                ],
            ],
            '__url' => '/content/objects/42/versions/1',
        ];

        $VersionUpdate = $this->getParser();
        $VersionUpdate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test VersionUpdate parser throwing exception on missing field value.
     */
    public function testParseExceptionOnMissingFieldValue()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'fieldValue\' element for the \'subject\' identifier in VersionUpdate.');
        $inputArray = [
            'initialLanguageCode' => 'eng-US',
            'fields' => [
                'field' => [
                    [
                        'fieldDefinitionIdentifier' => 'subject',
                    ],
                ],
            ],
            '__url' => '/content/objects/42/versions/1',
        ];

        $VersionUpdate = $this->getParser();
        $VersionUpdate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the VersionUpdate parser.
     *
     * @return \Ibexa\Rest\Server\Input\Parser\VersionUpdate
     */
    protected function internalGetParser()
    {
        return new VersionUpdate(
            $this->getContentServiceMock(),
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
            ->with(42, 'subject', [])
            ->willReturn('foo');

        return $fieldTypeParserMock;
    }

    /**
     * Get the Content service mock object.
     *
     * @return \Ibexa\Contracts\Core\Repository\ContentService
     */
    protected function getContentServiceMock()
    {
        $contentServiceMock = $this->createMock(ContentService::class);

        $contentServiceMock->expects($this->any())
            ->method('newContentUpdateStruct')
            ->willReturn(
                new ContentUpdateStruct()
            );

        return $contentServiceMock;
    }

    public function getParseHrefExpectationsMap()
    {
        return [
            ['/content/objects/42/versions/1', 'contentId', 42],
        ];
    }
}

class_alias(VersionUpdateTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\VersionUpdateTest');
