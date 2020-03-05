<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use eZ\Publish\API\Repository\Values\Content\Query;
use EzSystems\EzPlatformRest\Server\Input\Parser\ViewInput;
use EzSystems\EzPlatformRest\Server\Values\RestViewInput;

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
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\ViewInput
     */
    protected function internalGetParser()
    {
        return new ViewInput();
    }
}
