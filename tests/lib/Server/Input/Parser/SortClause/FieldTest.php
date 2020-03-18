<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser\SortClause;

use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause\Field;
use EzSystems\EzPlatformRest\Server\Input\Parser\SortClause\Field as FieldParser;
use EzSystems\EzPlatformRest\Tests\Server\Input\Parser\BaseTest;
use EzSystems\EzPlatformRest\Exceptions\Parser;

class FieldTest extends BaseTest
{
    /**
     * Tests the Field parser.
     */
    public function testParse()
    {
        $inputArray = [
            'Field' => [
                'identifier' => 'content/field',
                'direction' => Query::SORT_ASC,
            ],
        ];

        $fieldParser = $this->getParser();
        $result = $fieldParser->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertEquals(
            new Field('content', 'field', Query::SORT_ASC),
            $result,
            'Field parser not created correctly.'
        );
    }

    /**
     * Test Field parser throwing exception on missing sort clause.
     */
    public function testParseExceptionOnMissingSortClause()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('The <Field> Sort Clause doesn\'t exist in the input structure');
        $inputArray = [
            'name' => 'Keep on mocking in the free world',
        ];

        $fieldParser = $this->getParser();
        $fieldParser->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test Field parser throwing exception on invalid direction format.
     */
    public function testParseExceptionOnInvalidDirectionFormat()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Invalid direction format in <Field> sort clause');
        $inputArray = [
            'Field' => [
                'identifier' => 'content/field',
                'direction' => 'mock',
            ],
        ];

        $fieldParser = $this->getParser();
        $fieldParser->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the Field parser.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\SortClause\Field
     */
    protected function internalGetParser()
    {
        return new FieldParser();
    }
}
