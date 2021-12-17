<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Output\Generator;

use Ibexa\Rest\Output\Generator\Xml;
use Ibexa\Rest\Output\Generator\Xml\FieldTypeHashGenerator;
use Ibexa\Tests\Rest\Output\GeneratorTest;

require_once __DIR__ . '/../GeneratorTest.php';

/**
 * Xml generator test class.
 */
class XmlTest extends GeneratorTest
{
    public function testGeneratorDocument()
    {
        $generator = $this->getGenerator();

        $generator->startDocument('test');

        $this->assertSame(
            file_get_contents(__DIR__ . '/_fixtures/' . __FUNCTION__ . '.xml'),
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
            file_get_contents(__DIR__ . '/_fixtures/' . __FUNCTION__ . '.xml'),
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
            file_get_contents(__DIR__ . '/_fixtures/' . __FUNCTION__ . '.xml'),
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
            file_get_contents(__DIR__ . '/_fixtures/' . __FUNCTION__ . '.xml'),
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
            file_get_contents(__DIR__ . '/_fixtures/' . __FUNCTION__ . '.xml'),
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
            file_get_contents(__DIR__ . '/_fixtures/' . __FUNCTION__ . '.xml'),
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
            file_get_contents(__DIR__ . '/_fixtures/' . __FUNCTION__ . '.xml'),
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
            file_get_contents(__DIR__ . '/_fixtures/' . __FUNCTION__ . '.xml'),
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
            file_get_contents(__DIR__ . '/_fixtures/' . __FUNCTION__ . '.xml'),
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
            file_get_contents(__DIR__ . '/_fixtures/' . __FUNCTION__ . '.xml'),
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

        $generator->startValueElement('element', 'element value 2', ['attribute' => 'attribute value 2']);
        $generator->endValueElement('element');

        $generator->endHashElement('elements');

        $this->assertSame(
            file_get_contents(__DIR__ . '/_fixtures/' . __FUNCTION__ . '.xml'),
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
            file_get_contents(__DIR__ . '/_fixtures/' . __FUNCTION__ . '.xml'),
            $generator->endDocument('test')
        );
    }

    public function testGetMediaType()
    {
        $generator = $this->getGenerator();

        $this->assertEquals(
            'application/vnd.ez.api.Section+xml',
            $generator->getMediaType('Section')
        );
    }

    public function testSerializeBool()
    {
        $generator = $this->getGenerator();

        $this->assertTrue($generator->serializeBool(true) === 'true');
        $this->assertTrue($generator->serializeBool(false) === 'false');
        $this->assertTrue($generator->serializeBool('notbooleanbuttrue') === 'true');
    }

    protected function getGenerator()
    {
        if (!isset($this->generator)) {
            $this->generator = new Xml(
                $this->createMock(FieldTypeHashGenerator::class)
            );
        }
        $this->generator->setFormatOutput(true);

        return $this->generator;
    }
}

class_alias(XmlTest::class, 'EzSystems\EzPlatformRest\Tests\Output\Generator\XmlTest');
