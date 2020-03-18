<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query;
use EzSystems\EzPlatformRest\Server\Input\Parser\ViewInputOneDotOne;
use EzSystems\EzPlatformRest\Server\Values\RestViewInput;
use EzSystems\EzPlatformRest\Exceptions\Parser;

class ViewInputOneDotOneTest extends BaseTest
{
    /**
     * Tests the ViewInput parser.
     */
    public function testParseContentQuery()
    {
        $inputArray = [
            'identifier' => 'Query identifier',
            'ContentQuery' => [],
        ];

        $parser = $this->getParser();
        $parsingDispatcher = $this->getParsingDispatcherMock();
        $parsingDispatcher
            ->expects($this->once())
            ->method('parse')
            ->with($inputArray['ContentQuery'], 'application/vnd.ez.api.internal.ContentQuery')
            ->willReturn(new Query());

        $result = $parser->parse($inputArray, $parsingDispatcher);

        $expectedViewInput = new RestViewInput();
        $expectedViewInput->identifier = 'Query identifier';
        $expectedViewInput->query = new Query();

        $this->assertEquals($expectedViewInput, $result, 'RestViewInput not created correctly.');
    }

    /**
     * Tests the ViewInput parser.
     */
    public function testParseLocationQuery()
    {
        $inputArray = [
            'identifier' => 'Query identifier',
            'LocationQuery' => [],
        ];

        $parser = $this->getParser();
        $parsingDispatcher = $this->getParsingDispatcherMock();
        $parsingDispatcher
            ->expects($this->once())
            ->method('parse')
            ->with($inputArray['LocationQuery'], 'application/vnd.ez.api.internal.LocationQuery')
            ->willReturn(new LocationQuery());

        $result = $parser->parse($inputArray, $parsingDispatcher);

        $expectedViewInput = new RestViewInput();
        $expectedViewInput->identifier = 'Query identifier';
        $expectedViewInput->query = new LocationQuery();

        $this->assertEquals($expectedViewInput, $result, 'RestViewInput not created correctly.');
    }

    public function testThrowsExceptionOnMissingIdentifier()
    {
        $this->expectException(Parser::class);
        $inputArray = ['Query' => []];
        $this->getParser()->parse($inputArray, $this->getParsingDispatcherMock());
    }

    public function testThrowsExceptionOnMissingQuery()
    {
        $this->expectException(Parser::class);
        $inputArray = ['identifier' => 'foo'];
        $this->getParser()->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the session input parser.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\ViewInput
     */
    protected function internalGetParser()
    {
        return new ViewInputOneDotOne();
    }
}
