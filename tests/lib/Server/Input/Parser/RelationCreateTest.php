<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use EzSystems\EzPlatformRest\Server\Input\Parser\RelationCreate;
use EzSystems\EzPlatformRest\Exceptions\Parser;

class RelationCreateTest extends BaseTest
{
    /**
     * Tests the RelationCreate parser.
     */
    public function testParse()
    {
        $inputArray = [
            'Destination' => [
                '_href' => '/content/objects/42',
            ],
        ];

        $relationCreate = $this->getParser();
        $result = $relationCreate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertEquals(
            42,
            $result,
            'RelationCreate struct not parsed correctly.'
        );
    }

    /**
     * Test RelationCreate parser throwing exception on missing Destination.
     */
    public function testParseExceptionOnMissingDestination()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing or invalid \'Destination\' element for RelationCreate.');
        $inputArray = [];

        $relationCreate = $this->getParser();
        $relationCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test RelationCreate parser throwing exception on missing Destination href.
     */
    public function testParseExceptionOnMissingDestinationHref()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'_href\' attribute for the Destination element in RelationCreate.');
        $inputArray = [
            'Destination' => [],
        ];

        $relationCreate = $this->getParser();
        $relationCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the RelationCreate parser.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\RelationCreate
     */
    protected function internalGetParser()
    {
        $parser = new RelationCreate();
        $parser->setRequestParser($this->getRequestParserMock());

        return $parser;
    }

    public function getParseHrefExpectationsMap()
    {
        return [
            ['/content/objects/42', 'contentId', 42],
        ];
    }
}
