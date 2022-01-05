<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\Values\ContentType\FieldDefinitionUpdateStruct;
use Ibexa\Contracts\Rest\Exceptions\Parser;
use Ibexa\Core\Repository\ContentTypeService;
use Ibexa\Core\Repository\Values\ContentType\ContentType;
use Ibexa\Core\Repository\Values\ContentType\ContentTypeDraft;
use Ibexa\Core\Repository\Values\ContentType\FieldDefinition;
use Ibexa\Core\Repository\Values\ContentType\FieldDefinitionCollection;
use Ibexa\Rest\Input\FieldTypeParser;
use Ibexa\Rest\Server\Input\Parser\FieldDefinitionUpdate;

/**
 * @todo Test with fieldSettings and validatorConfiguration when specified
 */
class FieldDefinitionUpdateTest extends BaseTest
{
    /**
     * Tests the FieldDefinitionUpdate parser.
     */
    public function testParse()
    {
        $inputArray = $this->getInputArray();

        $fieldDefinitionUpdate = $this->getParser();
        $result = $fieldDefinitionUpdate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            FieldDefinitionUpdateStruct::class,
            $result,
            'FieldDefinitionUpdateStruct not created correctly.'
        );

        $this->assertEquals(
            'title',
            $result->identifier,
            'identifier not created correctly'
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
     * Test FieldDefinitionUpdate parser throwing exception on invalid names.
     */
    public function testParseExceptionOnInvalidNames()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Invalid \'names\' element for FieldDefinitionUpdate.');
        $inputArray = $this->getInputArray();
        unset($inputArray['names']['value']);

        $fieldDefinitionUpdate = $this->getParser();
        $fieldDefinitionUpdate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test FieldDefinitionUpdate parser throwing exception on invalid descriptions.
     */
    public function testParseExceptionOnInvalidDescriptions()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Invalid \'descriptions\' element for FieldDefinitionUpdate.');
        $inputArray = $this->getInputArray();
        unset($inputArray['descriptions']['value']);

        $fieldDefinitionUpdate = $this->getParser();
        $fieldDefinitionUpdate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the FieldDefinitionUpdate parser.
     *
     * @return \Ibexa\Rest\Server\Input\Parser\FieldDefinitionUpdate
     */
    protected function internalGetParser()
    {
        return new FieldDefinitionUpdate(
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
            ->method('newFieldDefinitionUpdateStruct')
            ->willReturn(
                new FieldDefinitionUpdateStruct()
            );

        $contentTypeServiceMock->expects($this->any())
            ->method('loadContentTypeDraft')
            ->with($this->equalTo(42))
            ->willReturn(
                new ContentTypeDraft(
                    [
                            'innerContentType' => new ContentType([
                                'fieldDefinitions' => new FieldDefinitionCollection([
                                    new FieldDefinition(
                                        [
                                            'id' => 24,
                                            'fieldTypeIdentifier' => 'ezstring',
                                        ]
                                    ),
                                ]),
                            ]),
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
            '__url' => '/content/types/42/draft/fieldDefinitions/24',
            'identifier' => 'title',
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

    public function getParseHrefExpectationsMap()
    {
        return [
            ['/content/types/42/draft/fieldDefinitions/24', 'contentTypeId', 42],
            ['/content/types/42/draft/fieldDefinitions/24', 'fieldDefinitionId', 24],
        ];
    }
}

class_alias(FieldDefinitionUpdateTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\FieldDefinitionUpdateTest');
