<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Input;

use Ibexa\Contracts\Rest\Input\Parser;
use Ibexa\Contracts\Rest\Input\ParsingDispatcher;
use PHPUnit\Framework\TestCase;

/**
 * ParsingDispatcher test class.
 */
class ParsingDispatcherTest extends TestCase
{
    public function testParseMissingContentType()
    {
        $this->expectException(\Ibexa\Contracts\Rest\Exceptions\Parser::class);

        $dispatcher = new ParsingDispatcher();

        $dispatcher->parse([], 'text/unknown');
    }

    public function testParse()
    {
        $parser = $this->createParserMock();
        $dispatcher = new ParsingDispatcher(['text/html' => $parser]);

        $parser
            ->expects($this->at(0))
            ->method('parse')
            ->with([42], $dispatcher)
            ->willReturn(23);

        $this->assertSame(
            23,
            $dispatcher->parse([42], 'text/html')
        );
    }

    /**
     * Verifies that the charset specified in the Content-Type is ignored.
     */
    public function testParseCharset()
    {
        $parser = $this->createParserMock();
        $dispatcher = new ParsingDispatcher(['text/html' => $parser]);

        $parser
            ->expects($this->at(0))
            ->method('parse')
            ->with([42], $dispatcher)
            ->willReturn(23);

        $this->assertSame(
            23,
            $dispatcher->parse([42], 'text/html; charset=UTF-8; version=1.0')
        );
    }

    public function testParseVersion()
    {
        $parserVersionOne = $this->createParserMock();
        $parserVersionTwo = $this->createParserMock();
        $dispatcher = new ParsingDispatcher(
            [
                'text/html' => $parserVersionOne,
                'text/html; version=2' => $parserVersionTwo,
            ]
        );

        $parserVersionOne->expects($this->never())->method('parse');
        $parserVersionTwo->expects($this->once())->method('parse');

        $dispatcher->parse([42], 'text/html; version=2');
    }

    public function testParseStripFormat()
    {
        $parser = $this->createParserMock();
        $dispatcher = new ParsingDispatcher(['text/html' => $parser]);

        $parser
            ->expects($this->at(0))
            ->method('parse')
            ->with([42], $dispatcher)
            ->willReturn(23);

        $this->assertSame(
            23,
            $dispatcher->parse([42], 'text/html+json')
        );
    }

    /**
     * @return \Ibexa\Contracts\Rest\Input\Parser|\PHPUnit\Framework\MockObject\MockObject
     */
    private function createParserMock()
    {
        return $this->createMock(Parser::class);
    }
}

class_alias(ParsingDispatcherTest::class, 'EzSystems\EzPlatformRest\Tests\Input\ParsingDispatcherTest');
