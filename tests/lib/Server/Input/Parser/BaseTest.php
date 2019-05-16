<?php

/**
 * File containing a test class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use EzSystems\EzPlatformRest\Input;
use EzSystems\EzPlatformRest\Tests\Server\BaseTest as ParentBaseTest;
use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\RequestParser;

/**
 * Base test for input parsers.
 */
abstract class BaseTest extends ParentBaseTest
{
    /**
     * @var \EzSystems\EzPlatformRest\Input\ParsingDispatcher|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $parsingDispatcherMock;

    /**
     * @var \EzSystems\EzPlatformRest\RequestParser|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $requestParserMock;

    /**
     * @var \EzSystems\EzPlatformRest\Input\ParserTools
     */
    protected $parserTools;

    /**
     * Get the parsing dispatcher.
     *
     * @return \EzSystems\EzPlatformRest\Input\ParsingDispatcher
     */
    protected function getParsingDispatcherMock()
    {
        if (!isset($this->parsingDispatcherMock)) {
            $this->parsingDispatcherMock = $this->createMock(ParsingDispatcher::class);
        }

        return $this->parsingDispatcherMock;
    }

    /**
     * Returns the parseHref invocation expectations, as an array of:
     * 0. route to parse the href from (/content/objects/59
     * 1. attribute name we are looking for (contentId)
     * 2. expected return value (59)*.
     *
     * @return array
     */
    public function getParseHrefExpectationsMap()
    {
        return array();
    }

    /**
     * Get the Request parser.
     *
     * @return \EzSystems\EzPlatformRest\RequestParser|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getRequestParserMock()
    {
        if (!isset($this->requestParserMock)) {
            $that = &$this;

            $callback = function ($href, $attribute) use ($that) {
                foreach ($that->getParseHrefExpectationsMap() as $map) {
                    if ($map[0] == $href && $map[1] == $attribute) {
                        if ($map[2] instanceof \Exception) {
                            throw $map[2];
                        } else {
                            return $map[2];
                        }
                    }
                }

                return null;
            };

            $this->requestParserMock = $this->createMock(RequestParser::class);

            $this->requestParserMock
                ->expects($this->any())
                ->method('parseHref')
                ->will($this->returnCallback($callback));
        }

        return $this->requestParserMock;
    }

    /**
     * Get the parser tools.
     *
     * @return \EzSystems\EzPlatformRest\Input\ParserTools
     */
    protected function getParserTools()
    {
        if (!isset($this->parserTools)) {
            $this->parserTools = new Input\ParserTools();
        }

        return $this->parserTools;
    }

    protected function getParser()
    {
        $parser = $this->internalGetParser();
        $parser->setRequestParser($this->getRequestParserMock());

        return $parser;
    }

    /**
     * Must return the tested parser object.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\Base
     */
    abstract protected function internalGetParser();
}
