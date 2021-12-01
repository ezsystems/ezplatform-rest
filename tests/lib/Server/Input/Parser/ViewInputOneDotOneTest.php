<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\Values\Content\LocationQuery;
use Ibexa\Contracts\Core\Repository\Values\Content\Query;
use Ibexa\Contracts\Rest\Exceptions\Parser;
use Ibexa\Rest\Server\Input\Parser\ViewInputOneDotOne;
use Ibexa\Rest\Server\Values\RestViewInput;

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
     * @return \Ibexa\Rest\Server\Input\Parser\ViewInput
     */
    protected function internalGetParser()
    {
        return new ViewInputOneDotOne();
    }
}

class_alias(ViewInputOneDotOneTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\ViewInputOneDotOneTest');
