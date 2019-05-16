<?php

/**
 * File containing the SessionInputTest class.
 *
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
        $inputArray = array(
            'identifier' => 'Query identifier',
            'Query' => [],
        );

        $parser = $this->getParser();
        $parsingDispatcher = $this->getParsingDispatcherMock();
        $parsingDispatcher
            ->expects($this->once())
            ->method('parse')
            ->with($inputArray['Query'], 'application/vnd.ez.api.internal.ContentQuery')
            ->will($this->returnValue(new Query()));

        $result = $parser->parse($inputArray, $parsingDispatcher);

        $expectedViewInput = new RestViewInput();
        $expectedViewInput->identifier = 'Query identifier';
        $expectedViewInput->query = new Query();

        $this->assertEquals($expectedViewInput, $result, 'RestViewInput not created correctly.');
    }

    /**
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     */
    public function testThrowsExceptionOnMissingIdentifier()
    {
        $inputArray = ['Query' => []];
        $this->getParser()->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     */
    public function testThrowsExceptionOnMissingQuery()
    {
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
