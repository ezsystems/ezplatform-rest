<?php

/**
 * File containing the DispatcherTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Input;

use EzSystems\EzPlatformRest;
use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\Input\Handler;
use PHPUnit\Framework\TestCase;

/**
 * Dispatcher test class.
 */
class DispatcherTest extends TestCase
{
    protected function getParsingDispatcherMock()
    {
        return $this->createMock(ParsingDispatcher::class);
    }

    /**
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     */
    public function testParseMissingContentType()
    {
        $message = new EzPlatformRest\Message();

        $parsingDispatcher = $this->getParsingDispatcherMock();
        $dispatcher = new EzPlatformRest\Input\Dispatcher($parsingDispatcher);

        $dispatcher->parse($message);
    }

    /**
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     */
    public function testParseInvalidContentType()
    {
        $message = new EzPlatformRest\Message(
            array(
                'Content-Type' => 'text/html',
            )
        );

        $parsingDispatcher = $this->getParsingDispatcherMock();
        $dispatcher = new EzPlatformRest\Input\Dispatcher($parsingDispatcher);

        $dispatcher->parse($message);
    }

    /**
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     */
    public function testParseMissingFormatHandler()
    {
        $message = new EzPlatformRest\Message(
            array(
                'Content-Type' => 'text/html+unknown',
            )
        );

        $parsingDispatcher = $this->getParsingDispatcherMock();
        $dispatcher = new EzPlatformRest\Input\Dispatcher($parsingDispatcher);

        $dispatcher->parse($message);
    }

    public function testParse()
    {
        $message = new EzPlatformRest\Message(
            array(
                'Content-Type' => 'text/html+format',
            ),
            'Hello world!'
        );

        $parsingDispatcher = $this->getParsingDispatcherMock();
        $parsingDispatcher
            ->expects($this->at(0))
            ->method('parse')
            ->with(array(42), 'text/html')
            ->will($this->returnValue(23));

        $handler = $this->createMock(Handler::class);
        $handler
            ->expects($this->at(0))
            ->method('convert')
            ->with('Hello world!')
            ->will($this->returnValue(array(array(42))));

        $dispatcher = new EzPlatformRest\Input\Dispatcher($parsingDispatcher, array('format' => $handler));

        $this->assertSame(
            23,
            $dispatcher->parse($message)
        );
    }

    /**
     * @todo This is a test for a feature that needs refactoring. There must be
     * a sensible way to submit the called URL to the parser.
     */
    public function testParseSpecialUrlHeader()
    {
        $message = new EzPlatformRest\Message(
            array(
                'Content-Type' => 'text/html+format',
                'Url' => '/foo/bar',
            ),
            'Hello world!'
        );

        $parsingDispatcher = $this->getParsingDispatcherMock();
        $parsingDispatcher
            ->expects($this->at(0))
            ->method('parse')
            ->with(array('someKey' => 'someValue', '__url' => '/foo/bar'), 'text/html')
            ->will($this->returnValue(23));

        $handler = $this->createMock(Handler::class);
        $handler
            ->expects($this->at(0))
            ->method('convert')
            ->with('Hello world!')
            ->will(
                $this->returnValue(
                    array(
                        array(
                            'someKey' => 'someValue',
                        ),
                    )
                )
            );

        $dispatcher = new EzPlatformRest\Input\Dispatcher($parsingDispatcher, array('format' => $handler));

        $this->assertSame(
            23,
            $dispatcher->parse($message)
        );
    }

    public function testParseMediaTypeCharset()
    {
        $message = new EzPlatformRest\Message(
            array(
                'Content-Type' => 'text/html+format; version=1.1; charset=UTF-8',
                'Url' => '/foo/bar',
            ),
            'Hello world!'
        );

        $parsingDispatcher = $this->getParsingDispatcherMock();
        $parsingDispatcher
            ->expects($this->any())
            ->method('parse')
            ->with($this->anything(), 'text/html; version=1.1');

        $handler = $this->createMock(Handler::class);
        $handler
            ->expects($this->any())
            ->method('convert')
            ->will($this->returnValue(array()));

        $dispatcher = new EzPlatformRest\Input\Dispatcher($parsingDispatcher, array('format' => $handler));

        $dispatcher->parse($message);
    }
}
