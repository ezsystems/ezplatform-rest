<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\Values\ContentType\FieldDefinitionCreateStruct;
use Ibexa\Contracts\Rest\Exceptions\Parser;
use Ibexa\Core\Repository\ContentTypeService;
use Ibexa\Rest\Input\FieldTypeParser;
use Ibexa\Rest\Server\Input\Parser\FieldDefinitionCreate;

/**
 * @todo Test with fieldSettings and validatorConfiguration when specified
 */
class FieldDefinitionCreateTest extends BaseTest
{
    /**
     * Tests the FieldDefinitionCreate parser.
     */
    public function testParse()
    {
        $inputArray = $this->getInputArray();

        $fieldDefinitionCreate = $this->getParser();
        $result = $fieldDefinitionCreate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            FieldDefinitionCreateStruct::class,
            $result,
            'FieldDefinitionCreateStruct not created correctly.'
        );

        $this->assertEquals(
            'title',
            $result->identifier,
            'identifier not created correctly'
        );

        $this->assertEquals(
            'ezstring',
            $result->fieldTypeIdentifier,
            'fieldTypeIdentifier not created correctly'
        );

        $this->assertEquals(
            'content',
            $result->fieldGroup,
            'fieldGroup not created correctly'
        );

        $this->assertEquals(
            1,
            $result->position,
            'position not created correctly'
        );

        $this->assertTrue(
            $result->isTranslatable,
            'isTranslatable not created correctly'
        );

        $this->assertTrue(
            $result->isRequired,
            'isRequired not created correctly'
        );

        $this->assertTrue(
            $result->isInfoCollector,
            'isInfoCollector not created correctly'
        );

        $this->assertTrue(
            $result->isSearchable,
            'isSearchable not created correctly'
        );

        $this->assertEquals(
            'New title',
            $result->defaultValue,
            'defaultValue not created correctly'
        );

        $this->assertEquals(
            ['eng-US' => 'Title'],
            $result->names,
            'names not created correctly'
        );

        $this->assertEquals(
            ['eng-US' => 'This is the title'],
            $result->descriptions,
            'descriptions not created correctly'
        );

        $this->assertEquals(
            ['textRows' => 24],
            $result->fieldSettings,
            'fieldSettings not created correctly'
        );

        $this->assertEquals(
            [
                'StringLengthValidator' => [
                    'minStringLength' => 12,
                    'maxStringLength' => 24,
                ],
            ],
            $result->validatorConfiguration,
            'validatorConfiguration not created correctly'
        );
    }

    /**
     * Test FieldDefinitionCreate parser throwing exception on missing identifier.
     */
    public function testParseExceptionOnMissingIdentifier()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'identifier\' element for FieldDefinitionCreate.');
        $inputArray = $this->getInputArray();
        unset($inputArray['identifier']);

        $fieldDefinitionCreate = $this->getParser();
        $fieldDefinitionCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test FieldDefinitionCreate parser throwing exception on missing fieldType.
     */
    public function testParseExceptionOnMissingFieldType()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'fieldType\' element for FieldDefinitionCreate.');
        $inputArray = $this->getInputArray();
        unset($inputArray['fieldType']);

        $fieldDefinitionCreate = $this->getParser();
        $fieldDefinitionCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test FieldDefinitionCreate parser throwing exception on invalid names.
     */
    public function testParseExceptionOnInvalidNames()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Invalid \'names\' element for FieldDefinitionCreate.');
        $inputArray = $this->getInputArray();
        unset($inputArray['names']['value']);

        $fieldDefinitionCreate = $this->getParser();
        $fieldDefinitionCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test FieldDefinitionCreate parser throwing exception on invalid descriptions.
     */
    public function testParseExceptionOnInvalidDescriptions()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Invalid \'descriptions\' element for FieldDefinitionCreate.');
        $inputArray = $this->getInputArray();
        unset($inputArray['descriptions']['value']);

        $fieldDefinitionCreate = $this->getParser();
        $fieldDefinitionCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the FieldDefinitionCreate parser.
     *
     * @return \Ibexa\Rest\Server\Input\Parser\FieldDefinitionCreate
     */
    protected function internalGetParser()
    {
        return new FieldDefinitionCreate(
            $this->getContentTypeServiceMock(),
            $this->getFieldTypeParserMock(),
            $this->getParserTools()
        );
    }

    /**
     * Get the FieldTypeParser mock object.
     *
     * @return \Ibexa\Rest\Input\FieldTypeParser
     */
    protected function getFieldTypeParserMock()
    {
        $fieldTypeParserMock = $this->createMock(FieldTypeParser::class);

        $fieldTypeParserMock->expects($this->any())
            ->method('parseValue')
            ->willReturn('New title');

        $fieldTypeParserMock->expects($this->any())
            ->method('parseFieldSettings')
            ->willReturn(['textRows' => 24]);

        $fieldTypeParserMock->expects($this->any())
            ->method('parseValidatorConfiguration')
            ->willReturn(
                [
                    'StringLengthValidator' => [
                        'minStringLength' => 12,
                        'maxStringLength' => 24,
                    ],
                ]
            );

        return $fieldTypeParserMock;
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
            ->method('newFieldDefinitionCreateStruct')
            ->with($this->equalTo('title'), $this->equalTo('ezstring'))
            ->willReturn(
                new FieldDefinitionCreateStruct(
                    [
                            'identifier' => 'title',
                            'fieldTypeIdentifier' => 'ezstring',
                        ]
                )
            );

        return $contentTypeServiceMock;
    }

    /**
     * Returns the array under test.
     *
     * @return array
     */
    protected function getInputArray()
    {
        return [
            'identifier' => 'title',
            'fieldType' => 'ezstring',
            'fieldGroup' => 'content',
            'position' => '1',
            'isTranslatable' => 'true',
            'isRequired' => 'true',
            'isInfoCollector' => 'true',
            'isSearchable' => 'true',
            'defaultValue' => 'New title',
            'names' => [
                'value' => [
                    [
                        '_languageCode' => 'eng-US',
                        '#text' => 'Title',
                    ],
                ],
            ],
            'descriptions' => [
                'value' => [
                    [
                        '_languageCode' => 'eng-US',
                        '#text' => 'This is the title',
                    ],
                ],
            ],
            // Note that ezstring does not support settings, but that is irrelevant for the test
            'fieldSettings' => [
                'textRows' => 24,
            ],
            'validatorConfiguration' => [
                'StringLengthValidator' => [
                    'minStringLength' => '12',
                    'maxStringLength' => '24',
                ],
            ],
        ];
    }
}

class_alias(FieldDefinitionCreateTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\FieldDefinitionCreateTest');
