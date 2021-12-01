<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Input;

use Ibexa\Contracts\Core\Repository\ContentService;
use Ibexa\Contracts\Core\Repository\ContentTypeService;
use Ibexa\Contracts\Core\Repository\FieldType;
use Ibexa\Contracts\Core\Repository\FieldTypeService;
use Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo;
use Ibexa\Contracts\Rest\FieldTypeProcessor;
use Ibexa\Core\Repository\Values\ContentType\ContentType;
use Ibexa\Core\Repository\Values\ContentType\FieldDefinition;
use Ibexa\Rest\FieldTypeProcessorRegistry;
use Ibexa\Rest\Input\FieldTypeParser;
use PHPUnit\Framework\TestCase;

/**
 * FieldTypeParser test class.
 */
class FieldTypeParserTest extends TestCase
{
    protected $contentServiceMock;

    protected $contentTypeServiceMock;

    protected $fieldTypeServiceMock;

    protected $contentTypeMock;

    protected $fieldTypeMock;

    protected $fieldTypeProcessorRegistryMock;

    protected $fieldTypeProcessorMock;

    public function setUp(): void
    {
        $this->contentServiceMock = $this->createMock(ContentService::class);
        $this->contentTypeServiceMock = $this->createMock(ContentTypeService::class);
        $this->fieldTypeServiceMock = $this->createMock(FieldTypeService::class);
        $this->contentTypeMock = $this->createMock(ContentType::class);
        $this->fieldTypeMock = $this->createMock(FieldType::class);
        $this->fieldTypeProcessorRegistryMock = $this->createMock(FieldTypeProcessorRegistry::class);
        $this->fieldTypeProcessorMock = $this->createMock(FieldTypeProcessor::class);
    }

    public function testParseFieldValue()
    {
        $fieldTypeParser = $this->getFieldTypeParser();

        $this->contentServiceMock->expects($this->once())
            ->method('loadContentInfo')
            ->with('23')
            ->willReturn(
                new ContentInfo(['contentTypeId' => '42'])
            );

        $contentTypeMock = $this->contentTypeMock;
        $this->contentTypeServiceMock->expects($this->once())
            ->method('loadContentType')
            ->with('42')
            ->willReturnCallback(
                // Avoid PHPUnit cloning
                static function () use ($contentTypeMock) {
                    return $contentTypeMock;
                }
            );

        $contentTypeMock->expects($this->once())
            ->method('getFieldDefinition')
            ->with($this->equalTo('my-field-definition'))
            ->willReturn(
                new FieldDefinition(
                    [
                        'fieldTypeIdentifier' => 'some-fancy-field-type',
                    ]
                )
            );

        $this->fieldTypeProcessorRegistryMock->expects($this->once())
            ->method('hasProcessor')
            ->with($this->equalTo('some-fancy-field-type'))
            ->willReturn(false);

        $fieldTypeMock = $this->fieldTypeMock;
        $this->fieldTypeServiceMock->expects($this->once())
            ->method('getFieldType')
            ->with($this->equalTo('some-fancy-field-type'))
            ->willReturnCallback(
                // Avoid PHPUnit cloning
                static function () use ($fieldTypeMock) {
                    return $fieldTypeMock;
                }
            );

        $fieldTypeMock->expects($this->once())
            ->method('fromHash')
            ->with($this->equalTo([1, 2, 3]))
            ->willReturn(['foo', 'bar']);

        $this->assertEquals(
            ['foo', 'bar'],
            $fieldTypeParser->parseFieldValue(
                '23',
                'my-field-definition',
                [1, 2, 3]
            )
        );
    }

    public function testParseValue()
    {
        $fieldTypeParser = $this->getFieldTypeParser();

        $this->fieldTypeProcessorRegistryMock->expects($this->once())
            ->method('hasProcessor')
            ->with($this->equalTo('some-fancy-field-type'))
            ->willReturn(false);

        $fieldTypeMock = $this->fieldTypeMock;
        $this->fieldTypeServiceMock->expects($this->once())
            ->method('getFieldType')
            ->with($this->equalTo('some-fancy-field-type'))
            ->willReturnCallback(
                // Avoid PHPUnit cloning
                static function () use ($fieldTypeMock) {
                    return $fieldTypeMock;
                }
            );

        $fieldTypeMock->expects($this->once())
            ->method('fromHash')
            ->with($this->equalTo([1, 2, 3]))
            ->willReturn(['foo', 'bar']);

        $this->assertEquals(
            ['foo', 'bar'],
            $fieldTypeParser->parseValue(
                'some-fancy-field-type',
                [1, 2, 3]
            )
        );
    }

    public function testParseValueWithPreProcessing()
    {
        $fieldTypeParser = $this->getFieldTypeParser();

        $this->fieldTypeProcessorRegistryMock->expects($this->once())
            ->method('hasProcessor')
            ->with($this->equalTo('some-fancy-field-type'))
            ->willReturn(true);

        $processor = $this->fieldTypeProcessorMock;
        $this->fieldTypeProcessorRegistryMock->expects($this->once())
            ->method('getProcessor')
            ->with($this->equalTo('some-fancy-field-type'))
            ->willReturnCallback(
                static function () use ($processor) {
                    return $processor;
                }
            );

        $processor->expects($this->once())
            ->method('preProcessValueHash')
            ->with([1, 2, 3])
            ->willReturn([4, 5, 6]);

        $fieldTypeMock = $this->fieldTypeMock;
        $this->fieldTypeServiceMock->expects($this->once())
            ->method('getFieldType')
            ->with($this->equalTo('some-fancy-field-type'))
            ->willReturnCallback(
                // Avoid PHPUnit cloning
                static function () use ($fieldTypeMock) {
                    return $fieldTypeMock;
                }
            );

        $fieldTypeMock->expects($this->once())
            ->method('fromHash')
            ->with($this->equalTo([4, 5, 6]))
            ->willReturn(['foo', 'bar']);

        $this->assertEquals(
            ['foo', 'bar'],
            $fieldTypeParser->parseValue(
                'some-fancy-field-type',
                [1, 2, 3]
            )
        );
    }

    public function testParseFieldSettings()
    {
        $fieldTypeParser = $this->getFieldTypeParser();

        $fieldTypeMock = $this->fieldTypeMock;
        $this->fieldTypeServiceMock->expects($this->once())
            ->method('getFieldType')
            ->with($this->equalTo('some-fancy-field-type'))
            ->willReturnCallback(
                // Avoid PHPUnit cloning
                static function () use ($fieldTypeMock) {
                    return $fieldTypeMock;
                }
            );

        $fieldTypeMock->expects($this->once())
            ->method('fieldSettingsFromHash')
            ->with($this->equalTo([1, 2, 3]))
            ->willReturn(['foo', 'bar']);

        $this->assertEquals(
            ['foo', 'bar'],
            $fieldTypeParser->parseFieldSettings(
                'some-fancy-field-type',
                [1, 2, 3]
            )
        );
    }

    public function testParseFieldSettingsWithPreProcessing()
    {
        $fieldTypeParser = $this->getFieldTypeParser();

        $this->fieldTypeProcessorRegistryMock->expects($this->once())
            ->method('hasProcessor')
            ->with($this->equalTo('some-fancy-field-type'))
            ->willReturn(true);

        $processor = $this->fieldTypeProcessorMock;
        $this->fieldTypeProcessorRegistryMock->expects($this->once())
            ->method('getProcessor')
            ->with($this->equalTo('some-fancy-field-type'))
            ->willReturnCallback(
                static function () use ($processor) {
                    return $processor;
                }
            );

        $processor->expects($this->once())
            ->method('preProcessFieldSettingsHash')
            ->with([1, 2, 3])
            ->willReturn([4, 5, 6]);

        $fieldTypeMock = $this->fieldTypeMock;
        $this->fieldTypeServiceMock->expects($this->once())
            ->method('getFieldType')
            ->with($this->equalTo('some-fancy-field-type'))
            ->willReturnCallback(
                // Avoid PHPUnit cloning
                static function () use ($fieldTypeMock) {
                    return $fieldTypeMock;
                }
            );

        $fieldTypeMock->expects($this->once())
            ->method('fieldSettingsFromHash')
            ->with($this->equalTo([4, 5, 6]))
            ->willReturn(['foo', 'bar']);

        $this->assertEquals(
            ['foo', 'bar'],
            $fieldTypeParser->parseFieldSettings(
                'some-fancy-field-type',
                [1, 2, 3]
            )
        );
    }

    public function testParseValidatorConfiguration()
    {
        $fieldTypeParser = $this->getFieldTypeParser();

        $fieldTypeMock = $this->fieldTypeMock;
        $this->fieldTypeServiceMock->expects($this->once())
            ->method('getFieldType')
            ->with($this->equalTo('some-fancy-field-type'))
            ->willReturnCallback(
                // Avoid PHPUnit cloning
                static function () use ($fieldTypeMock) {
                    return $fieldTypeMock;
                }
            );

        $fieldTypeMock->expects($this->once())
            ->method('validatorConfigurationFromHash')
            ->with($this->equalTo([1, 2, 3]))
            ->willReturn(['foo', 'bar']);

        $this->assertEquals(
            ['foo', 'bar'],
            $fieldTypeParser->parseValidatorConfiguration(
                'some-fancy-field-type',
                [1, 2, 3]
            )
        );
    }

    public function testParseValidatorConfigurationWithPreProcessing()
    {
        $fieldTypeParser = $this->getFieldTypeParser();

        $this->fieldTypeProcessorRegistryMock->expects($this->once())
            ->method('hasProcessor')
            ->with($this->equalTo('some-fancy-field-type'))
            ->willReturn(true);

        $processor = $this->fieldTypeProcessorMock;
        $this->fieldTypeProcessorRegistryMock->expects($this->once())
            ->method('getProcessor')
            ->with($this->equalTo('some-fancy-field-type'))
            ->willReturnCallback(
                static function () use ($processor) {
                    return $processor;
                }
            );

        $processor->expects($this->once())
            ->method('preProcessValidatorConfigurationHash')
            ->with([1, 2, 3])
            ->willReturn([4, 5, 6]);

        $fieldTypeMock = $this->fieldTypeMock;
        $this->fieldTypeServiceMock->expects($this->once())
            ->method('getFieldType')
            ->with($this->equalTo('some-fancy-field-type'))
            ->willReturnCallback(
                // Avoid PHPUnit cloning
                static function () use ($fieldTypeMock) {
                    return $fieldTypeMock;
                }
            );

        $fieldTypeMock->expects($this->once())
            ->method('validatorConfigurationFromHash')
            ->with($this->equalTo([4, 5, 6]))
            ->willReturn(['foo', 'bar']);

        $this->assertEquals(
            ['foo', 'bar'],
            $fieldTypeParser->parseValidatorConfiguration(
                'some-fancy-field-type',
                [1, 2, 3]
            )
        );
    }

    protected function getFieldTypeParser()
    {
        return new FieldTypeParser(
            $this->contentServiceMock,
            $this->contentTypeServiceMock,
            $this->fieldTypeServiceMock,
            $this->fieldTypeProcessorRegistryMock
        );
    }
}

class_alias(FieldTypeParserTest::class, 'EzSystems\EzPlatformRest\Tests\Input\FieldTypeParserTest');
