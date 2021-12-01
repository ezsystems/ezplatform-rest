<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\Values\Content\Query;
use Ibexa\Rest\Server\Input\Parser\ViewInput;
use Ibexa\Rest\Server\Values\RestViewInput;

class ViewInputTest extends BaseTest
{
    /**
     * Tests the ViewInput parser.
     */
    public function testParse()
    {
        $inputArray = [
            'identifier' => 'Query identifier',
            'Query' => [],
        ];

        $parser = $this->getParser();
        $parsingDispatcher = $this->getParsingDispatcherMock();
        $parsingDispatcher
            ->expects($this->once())
            ->method('parse')
            ->with($inputArray['Query'], 'application/vnd.ez.api.internal.ContentQuery')
            ->willReturn(new Query());

        $result = $parser->parse($inputArray, $parsingDispatcher);

        $expectedViewInput = new RestViewInput();
        $expectedViewInput->identifier = 'Query identifier';
        $expectedViewInput->query = new Query();

        $this->assertEquals($expectedViewInput, $result, 'RestViewInput not created correctly.');
    }

    public function testThrowsExceptionOnMissingIdentifier()
    {
        $this->expectException('EzSystems\EzPlatformRest\Exceptions\Parser');
        $inputArray = ['Query' => []];
        $this->getParser()->parse($inputArray, $this->getParsingDispatcherMock());
    }

    public function testThrowsExceptionOnMissingQuery()
    {
        $this->expectException('EzSystems\EzPlatformRest\Exceptions\Parser');
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
        return new ViewInput();
    }
}

class_alias(ViewInputTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\ViewInputTest');
