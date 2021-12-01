<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Output;

use Ibexa\Contracts\Core\Repository\FieldType as APIFieldType;
use Ibexa\Contracts\Core\Repository\FieldTypeService;
use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType as APIContentType;
use Ibexa\Contracts\Rest\FieldTypeProcessor;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Core\Repository\Values\ContentType\FieldDefinition;
use Ibexa\Rest\FieldTypeProcessorRegistry;
use Ibexa\Rest\Output\FieldTypeSerializer;
use PHPUnit\Framework\TestCase;

/**
 * FieldTypeSerializer test.
 */
class FieldTypeSerializerTest extends TestCase
{
    protected $fieldTypeServiceMock;

    protected $fieldTypeProcessorRegistryMock;

    protected $fieldTypeProcessorMock;

    protected $contentTypeMock;

    protected $fieldTypeMock;

    protected $generatorMock;

    public function testSerializeFieldValue()
    {
        $serializer = $this->getFieldTypeSerializer();

        $this->getGeneratorMock()->expects($this->once())
            ->method('generateFieldTypeHash')
            ->with(
                $this->equalTo('fieldValue'),
                $this->equalTo([23, 42])
            );

        $this->getContentTypeMock()->expects($this->once())
            ->method('getFieldDefinition')
            ->with(
                $this->equalTo('some-field')
            )->willReturn(
                new FieldDefinition(
                    [
                        'fieldTypeIdentifier' => 'myFancyFieldType',
                    ]
                )
            );

        $fieldTypeMock = $this->getFieldTypeMock();
        $this->getFieldTypeServiceMock()->expects($this->once())
            ->method('getFieldType')
            ->with($this->equalTo('myFancyFieldType'))
            ->willReturnCallback(
                static function () use ($fieldTypeMock) {
                    return $fieldTypeMock;
                }
            );

        $fieldTypeMock->expects($this->once())
            ->method('toHash')
            ->with($this->equalTo('my-field-value'))
            ->willReturn([23, 42]);

        $serializer->serializeFieldValue(
            $this->getGeneratorMock(),
            $this->getContentTypeMock(),
            new Field(
                [
                    'fieldDefIdentifier' => 'some-field',
                    'value' => 'my-field-value',
                ]
            )
        );
    }

    public function testSerializeFieldValueWithProcessor()
    {
        $serializer = $this->getFieldTypeSerializer();

        $this->getGeneratorMock()->expects($this->once())
            ->method('generateFieldTypeHash')
            ->with(
                $this->equalTo('fieldValue'),
                $this->equalTo(['post-processed'])
            );

        $this->getContentTypeMock()->expects($this->once())
            ->method('getFieldDefinition')
            ->with(
                $this->equalTo('some-field')
            )->willReturn(
                new FieldDefinition(
                    [
                            'fieldTypeIdentifier' => 'myFancyFieldType',
                        ]
                )
            );

        $processorMock = $this->getFieldTypeProcessorMock();
        $this->getFieldTypeProcessorRegistryMock()
            ->expects($this->once())
            ->method('hasProcessor')
            ->with('myFancyFieldType')
            ->willReturn(true);
        $this->getFieldTypeProcessorRegistryMock()
            ->expects($this->once())
            ->method('getProcessor')
            ->with('myFancyFieldType')
            ->willReturnCallback(
                static function () use ($processorMock) {
                    return $processorMock;
                }
            );
        $processorMock->expects($this->once())
            ->method('postProcessValueHash')
            ->with($this->equalTo([23, 42]))
            ->willReturn(['post-processed']);

        $fieldTypeMock = $this->getFieldTypeMock();
        $this->getFieldTypeServiceMock()->expects($this->once())
            ->method('getFieldType')
            ->with($this->equalTo('myFancyFieldType'))
            ->willReturnCallback(
                static function () use ($fieldTypeMock) {
                    return $fieldTypeMock;
                }
            );

        $fieldTypeMock->expects($this->once())
            ->method('getFieldTypeIdentifier')
            ->willReturn('myFancyFieldType');
        $fieldTypeMock->expects($this->once())
            ->method('toHash')
            ->with($this->equalTo('my-field-value'))
            ->willReturn([23, 42]);

        $serializer->serializeFieldValue(
            $this->getGeneratorMock(),
            $this->getContentTypeMock(),
            new Field(
                [
                    'fieldDefIdentifier' => 'some-field',
                    'value' => 'my-field-value',
                ]
            )
        );
    }

    public function testSerializeFieldDefaultValue()
    {
        $serializer = $this->getFieldTypeSerializer();

        $this->getGeneratorMock()->expects($this->once())
            ->method('generateFieldTypeHash')
            ->with(
                $this->equalTo('defaultValue'),
                $this->equalTo([23, 42])
            );

        $fieldTypeMock = $this->getFieldTypeMock();
        $this->getFieldTypeServiceMock()->expects($this->once())
            ->method('getFieldType')
            ->with($this->equalTo('myFancyFieldType'))
            ->willReturnCallback(
                static function () use ($fieldTypeMock) {
                    return $fieldTypeMock;
                }
            );

        $fieldTypeMock->expects($this->once())
            ->method('toHash')
            ->with($this->equalTo('my-field-value'))
            ->willReturn([23, 42]);

        $serializer->serializeFieldDefaultValue(
            $this->getGeneratorMock(),
            'myFancyFieldType',
            'my-field-value'
        );
    }

    public function testSerializeFieldSettings()
    {
        $serializer = $this->getFieldTypeSerializer();

        $this->getGeneratorMock()->expects($this->once())
            ->method('generateFieldTypeHash')
            ->with(
                $this->equalTo('fieldSettings'),
                $this->equalTo(['foo' => 'bar'])
            );

        $fieldTypeMock = $this->getFieldTypeMock();
        $this->getFieldTypeServiceMock()->expects($this->once())
            ->method('getFieldType')
            ->with($this->equalTo('myFancyFieldType'))
            ->willReturnCallback(
                static function () use ($fieldTypeMock) {
                    return $fieldTypeMock;
                }
            );

        $fieldTypeMock->expects($this->once())
            ->method('fieldSettingsToHash')
            ->with($this->equalTo('my-field-settings'))
            ->willReturn(['foo' => 'bar']);

        $serializer->serializeFieldSettings(
            $this->getGeneratorMock(),
            'myFancyFieldType',
            'my-field-settings'
        );
    }

    public function testSerializeFieldSettingsWithPostProcessing()
    {
        $serializer = $this->getFieldTypeSerializer();
        $fieldTypeMock = $this->getFieldTypeMock();

        $processorMock = $this->getFieldTypeProcessorMock();
        $this->getFieldTypeProcessorRegistryMock()
            ->expects($this->once())
            ->method('hasProcessor')
            ->with('myFancyFieldType')
            ->willReturn(true);
        $this->getFieldTypeProcessorRegistryMock()
            ->expects($this->once())
            ->method('getProcessor')
            ->with('myFancyFieldType')
            ->willReturnCallback(
                static function () use ($processorMock) {
                    return $processorMock;
                }
            );
        $processorMock->expects($this->once())
            ->method('postProcessFieldSettingsHash')
            ->with($this->equalTo(['foo' => 'bar']))
            ->willReturn(['post-processed']);

        $this->getGeneratorMock()->expects($this->once())
            ->method('generateFieldTypeHash')
            ->with(
                $this->equalTo('fieldSettings'),
                $this->equalTo(['post-processed'])
            );

        $this->getFieldTypeServiceMock()->expects($this->once())
            ->method('getFieldType')
            ->with($this->equalTo('myFancyFieldType'))
            ->willReturnCallback(
                static function () use ($fieldTypeMock) {
                    return $fieldTypeMock;
                }
            );

        $fieldTypeMock->expects($this->once())
            ->method('fieldSettingsToHash')
            ->with($this->equalTo('my-field-settings'))
            ->willReturn(['foo' => 'bar']);

        $serializer->serializeFieldSettings(
            $this->getGeneratorMock(),
            'myFancyFieldType',
            'my-field-settings'
        );
    }

    public function testSerializeValidatorConfiguration()
    {
        $serializer = $this->getFieldTypeSerializer();

        $this->getGeneratorMock()->expects($this->once())
            ->method('generateFieldTypeHash')
            ->with(
                $this->equalTo('validatorConfiguration'),
                $this->equalTo(['bar' => 'foo'])
            );

        $fieldTypeMock = $this->getFieldTypeMock();
        $this->getFieldTypeServiceMock()->expects($this->once())
            ->method('getFieldType')
            ->with($this->equalTo('myFancyFieldType'))
            ->willReturnCallback(
                static function () use ($fieldTypeMock) {
                    return $fieldTypeMock;
                }
            );

        $fieldTypeMock->expects($this->once())
            ->method('validatorConfigurationToHash')
            ->with($this->equalTo('validator-config'))
            ->willReturn(['bar' => 'foo']);

        $serializer->serializeValidatorConfiguration(
            $this->getGeneratorMock(),
            'myFancyFieldType',
            'validator-config'
        );
    }

    public function testSerializeValidatorConfigurationWithPostProcessing()
    {
        $serializer = $this->getFieldTypeSerializer();
        $fieldTypeMock = $this->getFieldTypeMock();

        $processorMock = $this->getFieldTypeProcessorMock();
        $this->getFieldTypeProcessorRegistryMock()
            ->expects($this->once())
            ->method('hasProcessor')
            ->with('myFancyFieldType')
            ->willReturn(true);
        $this->getFieldTypeProcessorRegistryMock()
            ->expects($this->once())
            ->method('getProcessor')
            ->with('myFancyFieldType')
            ->willReturnCallback(
                static function () use ($processorMock) {
                    return $processorMock;
                }
            );
        $processorMock->expects($this->once())
            ->method('postProcessValidatorConfigurationHash')
            ->with($this->equalTo(['bar' => 'foo']))
            ->willReturn(['post-processed']);

        $fieldTypeMock = $this->getFieldTypeMock();
        $this->getFieldTypeServiceMock()->expects($this->once())
            ->method('getFieldType')
            ->with($this->equalTo('myFancyFieldType'))
            ->willReturnCallback(
                static function () use ($fieldTypeMock) {
                    return $fieldTypeMock;
                }
            );

        $this->getGeneratorMock()->expects($this->once())
            ->method('generateFieldTypeHash')
            ->with(
                $this->equalTo('validatorConfiguration'),
                $this->equalTo(['post-processed'])
            );

        $this->getFieldTypeServiceMock()->expects($this->once())
            ->method('getFieldType')
            ->with($this->equalTo('myFancyFieldType'))
            ->willReturnCallback(
                static function () use ($fieldTypeMock) {
                    return $fieldTypeMock;
                }
            );

        $fieldTypeMock->expects($this->once())
            ->method('validatorConfigurationToHash')
            ->with($this->equalTo('validator-config'))
            ->willReturn(['bar' => 'foo']);

        $serializer->serializeValidatorConfiguration(
            $this->getGeneratorMock(),
            'myFancyFieldType',
            'validator-config'
        );
    }

    protected function getFieldTypeSerializer()
    {
        return new FieldTypeSerializer(
            $this->getFieldTypeServiceMock(),
            $this->getFieldTypeProcessorRegistryMock()
        );
    }

    protected function getFieldTypeServiceMock()
    {
        if (!isset($this->fieldTypeServiceMock)) {
            $this->fieldTypeServiceMock = $this->createMock(FieldTypeService::class);
        }

        return $this->fieldTypeServiceMock;
    }

    protected function getFieldTypeProcessorRegistryMock()
    {
        if (!isset($this->fieldTypeProcessorRegistryMock)) {
            $this->fieldTypeProcessorRegistryMock = $this->createMock(FieldTypeProcessorRegistry::class);
        }

        return $this->fieldTypeProcessorRegistryMock;
    }

    protected function getFieldTypeProcessorMock()
    {
        if (!isset($this->fieldTypeProcessorMock)) {
            $this->fieldTypeProcessorMock = $this->createMock(FieldTypeProcessor::class);
        }

        return $this->fieldTypeProcessorMock;
    }

    protected function getContentTypeMock()
    {
        if (!isset($this->contentTypeMock)) {
            $this->contentTypeMock = $this->createMock(APIContentType::class);
        }

        return $this->contentTypeMock;
    }

    protected function getFieldTypeMock()
    {
        if (!isset($this->fieldTypeMock)) {
            $this->fieldTypeMock = $this->createMock(APIFieldType::class);
        }

        return $this->fieldTypeMock;
    }

    protected function getGeneratorMock()
    {
        if (!isset($this->generatorMock)) {
            $this->generatorMock = $this->createMock(Generator::class);
        }

        return $this->generatorMock;
    }
}

class_alias(FieldTypeSerializerTest::class, 'EzSystems\EzPlatformRest\Tests\Output\FieldTypeSerializerTest');
