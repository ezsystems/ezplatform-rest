<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Input\Parser\SortClause;

use Ibexa\Contracts\Core\Repository\Values\Content\Query;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\SortClause\Field;
use Ibexa\Contracts\Rest\Exceptions\Parser;
use Ibexa\Rest\Server\Input\Parser\SortClause\Field as FieldParser;
use Ibexa\Tests\Rest\Server\Input\Parser\BaseTest;

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
     * @return \Ibexa\Rest\Server\Input\Parser\SortClause\Field
     */
    protected function internalGetParser()
    {
        return new FieldParser();
    }
}

class_alias(FieldTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\SortClause\FieldTest');
