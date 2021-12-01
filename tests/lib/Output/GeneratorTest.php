<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Output;

use Ibexa\Contracts\Rest\Output\Exceptions\OutputGeneratorException;
use PHPUnit\Framework\TestCase;

/**
 * Output generator test class.
 */
abstract class GeneratorTest extends TestCase
{
    /**
     * @var \Ibexa\Contracts\Rest\Output\Generator
     */
    protected $generator;

    /**
     * @return \Ibexa\Contracts\Rest\Output\Generator
     */
    abstract protected function getGenerator();

    public function testInvalidDocumentStart()
    {
        $this->expectException(OutputGeneratorException::class);

        $generator = $this->getGenerator();

        $generator->startDocument('test');
        $generator->startDocument('test');
    }

    public function testValidDocumentStartAfterReset()
    {
        $generator = $this->getGenerator();

        $generator->startDocument('test');
        $generator->reset();
        $generator->startDocument('test');

        $this->assertNotNull($generator->endDocument('test'));
    }

    public function testInvalidDocumentNameEnd()
    {
        $this->expectException(OutputGeneratorException::class);

        $generator = $this->getGenerator();

        $generator->startDocument('test');
        $generator->endDocument('invalid');
    }

    public function testInvalidOuterElementStart()
    {
        $this->expectException(OutputGeneratorException::class);

        $generator = $this->getGenerator();

        $generator->startObjectElement('element');
    }

    public function testInvalidElementEnd()
    {
        $this->expectException(OutputGeneratorException::class);

        $generator = $this->getGenerator();

        $generator->startDocument('test');
        $generator->startObjectElement('element');
        $generator->endObjectElement('invalid');
    }

    public function testInvalidDocumentEnd()
    {
        $this->expectException(OutputGeneratorException::class);

        $generator = $this->getGenerator();

        $generator->startDocument('test');
        $generator->startObjectElement('element');
        $generator->endDocument('test');
    }

    public function testInvalidAttributeOuterStart()
    {
        $this->expectException(OutputGeneratorException::class);

        $generator = $this->getGenerator();

        $generator->startAttribute('attribute', 'value');
    }

    public function testInvalidAttributeDocumentStart()
    {
        $this->expectException(OutputGeneratorException::class);

        $generator = $this->getGenerator();

        $generator->startDocument('test');
        $generator->startAttribute('attribute', 'value');
    }

    public function testInvalidAttributeListStart()
    {
        $this->expectException(OutputGeneratorException::class);

        $generator = $this->getGenerator();

        $generator->startDocument('test');
        $generator->startObjectElement('element');
        $generator->startList('list');
        $generator->startAttribute('attribute', 'value');
    }

    public function testInvalidValueElementOuterStart()
    {
        $this->expectException(OutputGeneratorException::class);

        $generator = $this->getGenerator();

        $generator->startValueElement('element', 'value');
    }

    public function testInvalidValueElementDocumentStart()
    {
        $this->expectException(OutputGeneratorException::class);

        $generator = $this->getGenerator();

        $generator->startDocument('test');
        $generator->startValueElement('element', 'value');
    }

    public function testInvalidListOuterStart()
    {
        $this->expectException(OutputGeneratorException::class);

        $generator = $this->getGenerator();

        $generator->startList('list');
    }

    public function testInvalidListDocumentStart()
    {
        $this->expectException(OutputGeneratorException::class);

        $generator = $this->getGenerator();

        $generator->startDocument('test');
        $generator->startList('list');
    }

    public function testInvalidListListStart()
    {
        $this->expectException(OutputGeneratorException::class);

        $generator = $this->getGenerator();

        $generator->startDocument('test');
        $generator->startObjectElement('element');
        $generator->startList('list');
        $generator->startList('attribute', 'value');
    }

    public function testEmptyDocument()
    {
        $generator = $this->getGenerator();

        $generator->startDocument('test');

        $this->assertTrue($generator->isEmpty());
    }

    public function testNonEmptyDocument()
    {
        $generator = $this->getGenerator();

        $generator->startDocument('test');
        $generator->startObjectElement('element');

        $this->assertFalse($generator->isEmpty());
    }
}

class_alias(GeneratorTest::class, 'EzSystems\EzPlatformRest\Tests\Output\GeneratorTest');
