<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser\SortClause;

use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause\DatePublished;
use EzSystems\EzPlatformRest\Server\Input\Parser\SortClause\DataKeyValueObjectClass;
use EzSystems\EzPlatformRest\Tests\Server\Input\Parser\BaseTest;
use EzSystems\EzPlatformRest\Exceptions\Parser;

class DataKeyValueObjectClassTest extends BaseTest
{
    /**
     * Tests the DataKeyValueObjectClass parser.
     */
    public function testParse()
    {
        $inputArray = [
            'DatePublished' => Query::SORT_ASC,
        ];

        $dataKeyValueObjectClass = $this->getParser();
        $result = $dataKeyValueObjectClass->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertEquals(
            new DatePublished(Query::SORT_ASC),
            $result,
            'DataKeyValueObjectClass parser not created correctly.'
        );
    }

    /**
     * Test DataKeyValueObjectClass parser throwing exception on missing sort clause.
     */
    public function testParseExceptionOnMissingSortClause()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('The <DatePublished> Sort Clause doesn\'t exist in the input structure');
        $inputArray = [
            'name' => 'Keep on mocking in the free world',
        ];

        $dataKeyValueObjectClass = $this->getParser();
        $dataKeyValueObjectClass->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test DataKeyValueObjectClass parser throwing exception on invalid direction format.
     */
    public function testParseExceptionOnInvalidDirectionFormat()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Invalid direction format in the <DatePublished> Sort Clause');
        $inputArray = [
            'DatePublished' => 'Jailhouse Mock',
        ];

        $dataKeyValueObjectClass = $this->getParser();
        $dataKeyValueObjectClass->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test DataKeyValueObjectClass parser throwing exception on nonexisting value object class.
     */
    public function testParseExceptionOnNonexistingValueObjectClass()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Value object class <eC\Pubish\APl\Repudiatory\BadValues\Discontent\Queezy\SantaClause\ThisClassIsExistentiallyChallenged> is not defined');
        $inputArray = [
            'DatePublished' => Query::SORT_ASC,
        ];

        $dataKeyValueObjectClass = new DataKeyValueObjectClass(
            'DatePublished',
            'eC\Pubish\APl\Repudiatory\BadValues\Discontent\Queezy\SantaClause\ThisClassIsExistentiallyChallenged'
        );
        $dataKeyValueObjectClass->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the DataKeyValueObjectClass parser.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\SortClause\DataKeyValueObjectClass
     */
    protected function internalGetParser()
    {
        return new DataKeyValueObjectClass(
            'DatePublished',
            'eZ\Publish\API\Repository\Values\Content\Query\SortClause\DatePublished'
        );
    }
}
