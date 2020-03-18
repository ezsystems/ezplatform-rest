<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use EzSystems\EzPlatformRest\Server\Input\Parser\URLWildcardCreate;
use EzSystems\EzPlatformRest\Exceptions\Parser;

class URLWildcardCreateTest extends BaseTest
{
    /**
     * Tests the URLWildcardCreate parser.
     */
    public function testParse()
    {
        $inputArray = [
            'sourceUrl' => '/source/url',
            'destinationUrl' => '/destination/url',
            'forward' => 'true',
        ];

        $urlWildcardCreate = $this->getParser();
        $result = $urlWildcardCreate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertEquals(
            [
                'sourceUrl' => '/source/url',
                'destinationUrl' => '/destination/url',
                'forward' => true,
            ],
            $result,
            'URLWildcardCreate not parsed correctly.'
        );
    }

    /**
     * Test URLWildcardCreate parser throwing exception on missing sourceUrl.
     */
    public function testParseExceptionOnMissingSourceUrl()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'sourceUrl\' value for URLWildcardCreate.');
        $inputArray = [
            'destinationUrl' => '/destination/url',
            'forward' => 'true',
        ];

        $urlWildcardCreate = $this->getParser();
        $urlWildcardCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test URLWildcardCreate parser throwing exception on missing destinationUrl.
     */
    public function testParseExceptionOnMissingDestinationUrl()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'destinationUrl\' value for URLWildcardCreate.');
        $inputArray = [
            'sourceUrl' => '/source/url',
            'forward' => 'true',
        ];

        $urlWildcardCreate = $this->getParser();
        $urlWildcardCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test URLWildcardCreate parser throwing exception on missing forward.
     */
    public function testParseExceptionOnMissingForward()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'forward\' value for URLWildcardCreate.');
        $inputArray = [
            'sourceUrl' => '/source/url',
            'destinationUrl' => '/destination/url',
        ];

        $urlWildcardCreate = $this->getParser();
        $urlWildcardCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the URLWildcard input parser.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\URLWildcardCreate
     */
    protected function internalGetParser()
    {
        $parser = new URLWildcardCreate($this->getParserTools());
        $parser->setRequestParser($this->getRequestParserMock());

        return $parser;
    }
}
