<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Output\Generator;

use Ibexa\Contracts\Rest\Output\Exceptions\OutputGeneratorException;
use Ibexa\Rest\Output\Generator\Json;
use Ibexa\Rest\Output\Generator\Json\FieldTypeHashGenerator;
use Ibexa\Tests\Rest\Output\GeneratorTest;

require_once __DIR__ . '/../GeneratorTest.php';

/**
 * Json output generator test class.
 */
class JsonTest extends GeneratorTest
{
    protected $generator;

    public function testGeneratorDocument()
    {
        $generator = $this->getGenerator();

        $generator->startDocument('test');

        $this->assertSame(
            '{}',
            $generator->endDocument('test')
        );
    }

    public function testGeneratorElement()
    {
        $generator = $this->getGenerator();

        $generator->startDocument('test');

        $generator->startObjectElement('element');
        $generator->endObjectElement('element');

        $this->assertSame(
            '{"element":{"_media-type":"application\/vnd.ez.api.element+json"}}',
            $generator->endDocument('test')
        );
    }

    public function testGeneratorElementMediaTypeOverwrite()
    {
        $generator = $this->getGenerator();

        $generator->startDocument('test');

        $generator->startObjectElement('element', 'User');
        $generator->endObjectElement('element');

        $this->assertSame(
            '{"element":{"_media-type":"application\/vnd.ez.api.User+json"}}',
            $generator->endDocument('test')
        );
    }

    public function testGeneratorStackedElement()
    {
        $generator = $this->getGenerator();

        $generator->startDocument('test');

        $generator->startObjectElement('element');

        $generator->startObjectElement('stacked');
        $generator->endObjectElement('stacked');

        $generator->endObjectElement('element');

        $this->assertSame(
            '{"element":{"_media-type":"application\/vnd.ez.api.element+json","stacked":{"_media-type":"application\/vnd.ez.api.stacked+json"}}}',
            $generator->endDocument('test')
        );
    }

    public function testGeneratorAttribute()
    {
        $generator = $this->getGenerator();

        $generator->startDocument('test');

        $generator->startObjectElement('element');

        $generator->attribute('attribute', 'value');

        $generator->endObjectElement('element');

        $this->assertSame(
            '{"element":{"_media-type":"application\/vnd.ez.api.element+json","_attribute":"value"}}',
            $generator->endDocument('test')
        );
    }

    public function testGeneratorStartEndAttribute()
    {
        $generator = $this->getGenerator();

        $generator->startDocument('test');

        $generator->startObjectElement('element');

        $generator->startAttribute('attribute', 'value');
        $generator->endAttribute('attribute');

        $generator->endObjectElement('element');

        $this->assertSame(
            '{"element":{"_media-type":"application\/vnd.ez.api.element+json","_attribute":"value"}}',
            $generator->endDocument('test')
        );
    }

    public function testGeneratorMultipleAttributes()
    {
        $generator = $this->getGenerator();

        $generator->startDocument('test');

        $generator->startObjectElement('element');

        $generator->attribute('attribute1', 'value');
        $generator->attribute('attribute2', 'value');

        $generator->endObjectElement('element');

        $this->assertSame(
            '{"element":{"_media-type":"application\/vnd.ez.api.element+json","_attribute1":"value","_attribute2":"value"}}',
            $generator->endDocument('test')
        );
    }

    public function testGeneratorValueElement()
    {
        $generator = $this->getGenerator();

        $generator->startDocument('test');

        $generator->startObjectElement('element');

        $generator->valueElement('value', '42');

        $generator->endObjectElement('element');

        $this->assertSame(
            '{"element":{"_media-type":"application\/vnd.ez.api.element+json","value":"42"}}',
            $generator->endDocument('test')
        );
    }

    public function testGeneratorStartEndValueElement()
    {
        $generator = $this->getGenerator();

        $generator->startDocument('test');

        $generator->startObjectElement('element');

        $generator->startValueElement('value', '42');
        $generator->endValueElement('value');

        $generator->endObjectElement('element');

        $this->assertSame(
            '{"element":{"_media-type":"application\/vnd.ez.api.element+json","value":"42"}}',
            $generator->endDocument('test')
        );
    }

    public function testGeneratorElementList()
    {
        $generator = $this->getGenerator();

        $generator->startDocument('test');

        $generator->startObjectElement('elementList');

        $generator->startList('elements');

        $generator->startObjectElement('element');
        $generator->endObjectElement('element');

        $generator->startObjectElement('element');
        $generator->endObjectElement('element');

        $generator->endList('elements');

        $generator->endObjectElement('elementList');

        $this->assertSame(
            '{"elementList":{"_media-type":"application\/vnd.ez.api.elementList+json","elements":[{"_media-type":"application\/vnd.ez.api.element+json"},{"_media-type":"application\/vnd.ez.api.element+json"}]}}',
            $generator->endDocument('test')
        );
    }

    public function testGeneratorHashElement()
    {
        $generator = $this->getGenerator();

        $generator->startDocument('test');

        $generator->startHashElement('elements');

        $generator->startValueElement('element', 'element value 1', ['attribute' => 'attribute value 1']);
        $generator->endValueElement('element');

        $generator->endHashElement('elements');

        $this->assertSame(
            '{"elements":{"element":{"_attribute":"attribute value 1","#text":"element value 1"}}}',
            $generator->endDocument('test')
        );
    }

    public function testGeneratorValueList()
    {
        $generator = $this->getGenerator();

        $generator->startDocument('test');
        $generator->startObjectElement('element');
        $generator->startList('simpleValue');

        $generator->startValueElement('simpleValue', 'value1');
        $generator->endValueElement('simpleValue');
        $generator->startValueElement('simpleValue', 'value2');
        $generator->endValueElement('simpleValue');

        $generator->endList('simpleValue');
        $generator->endObjectElement('element');

        $this->assertSame(
            '{"element":{"_media-type":"application\/vnd.ez.api.element+json","simpleValue":["value1","value2"]}}',
            $generator->endDocument('test')
        );
    }

    public function testGetMediaType()
    {
        $generator = $this->getGenerator();

        $this->assertEquals(
            'application/vnd.ez.api.Section+json',
            $generator->getMediaType('Section')
        );
    }

    public function testGeneratorMultipleElements()
    {
        $this->expectException(OutputGeneratorException::class);

        $generator = $this->getGenerator();

        $generator->startDocument('test');

        $generator->startObjectElement('element');
        $generator->endObjectElement('element');

        $generator->startObjectElement('element');
    }

    public function testGeneratorMultipleStackedElements()
    {
        $this->expectException(OutputGeneratorException::class);

        $generator = $this->getGenerator();

        $generator->startDocument('test');

        $generator->startObjectElement('element');

        $generator->startObjectElement('stacked');
        $generator->endObjectElement('stacked');

        $generator->startObjectElement('stacked');
    }

    public function testSerializeBool()
    {
        $generator = $this->getGenerator();

        $this->assertTrue($generator->serializeBool(true) === true);
        $this->assertTrue($generator->serializeBool(false) === false);
        $this->assertTrue($generator->serializeBool('notbooleanbuttrue') === true);
    }

    protected function getGenerator()
    {
        if (!isset($this->generator)) {
            $this->generator = new Json(
                $this->createMock(FieldTypeHashGenerator::class)
            );
        }

        return $this->generator;
    }
}

class_alias(JsonTest::class, 'EzSystems\EzPlatformRest\Tests\Output\Generator\JsonTest');
