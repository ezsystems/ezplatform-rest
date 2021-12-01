<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Input;

use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use Ibexa\Rest\Input\ParserTools;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ParserToolsTest extends TestCase
{
    public function testIsEmbeddedObjectReturnsTrue()
    {
        $parserTools = $this->getParserTools();

        $this->assertTrue(
            $parserTools->isEmbeddedObject(
                [
                    '_href' => '/foo/bar',
                    '_media-type' => 'application/some-type',
                    'id' => 23,
                ]
            )
        );
    }

    public function testIsEmbeddedObjectReturnsFalse()
    {
        $parserTools = $this->getParserTools();

        $this->assertFalse(
            $parserTools->isEmbeddedObject(
                [
                    '_href' => '/foo/bar',
                    '_media-type' => 'application/some-type',
                ]
            )
        );
    }

    public function testParseObjectElementEmbedded()
    {
        $parserTools = $this->getParserTools();

        $dispatcherMock = $this->createMock(ParsingDispatcher::class);
        $dispatcherMock->expects($this->once())
            ->method('parse')
            ->with(
                $this->isType('array'),
                $this->equalTo('application/my-type')
            );

        $parsingInput = [
            '_href' => '/foo/bar',
            '_media-type' => 'application/my-type',
            'someContent' => [],
        ];

        $this->assertEquals(
            '/foo/bar',
            $parserTools->parseObjectElement($parsingInput, $dispatcherMock)
        );
    }

    public function testParseObjectElementNotEmbedded()
    {
        $parserTools = $this->getParserTools();

        $dispatcherMock = $this->createMock(ParsingDispatcher::class);
        $dispatcherMock->expects($this->never())
            ->method('parse');

        $parsingInput = [
            '_href' => '/foo/bar',
            '_media-type' => 'application/my-type',
            '#someTextContent' => 'foo',
        ];

        $this->assertEquals(
            '/foo/bar',
            $parserTools->parseObjectElement($parsingInput, $dispatcherMock)
        );
    }

    public function testNormalParseBooleanValue()
    {
        $tools = $this->getParserTools();

        $this->assertTrue($tools->parseBooleanValue('true'));
        $this->assertTrue($tools->parseBooleanValue(true));
        $this->assertFalse($tools->parseBooleanValue('false'));
        $this->assertFalse($tools->parseBooleanValue(false));
    }

    public function testUnexpectedValueParseBooleanValue()
    {
        $this->expectException(RuntimeException::class);

        $this->getParserTools()->parseBooleanValue('whatever but not a boolean');
    }

    protected function getParserTools()
    {
        return new ParserTools();
    }
}

class_alias(ParserToolsTest::class, 'EzSystems\EzPlatformRest\Tests\Input\ParserToolsTest');
