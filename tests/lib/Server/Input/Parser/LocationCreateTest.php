<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use eZ\Publish\Core\Repository\LocationService;
use EzSystems\EzPlatformRest\Server\Input\Parser\LocationCreate;
use eZ\Publish\API\Repository\Values\Content\LocationCreateStruct;
use eZ\Publish\API\Repository\Values\Content\Location;
use EzSystems\EzPlatformRest\Exceptions\Parser;

class LocationCreateTest extends BaseTest
{
    /**
     * Tests the LocationCreate parser.
     */
    public function testParse()
    {
        $inputArray = [
            'ParentLocation' => [
                '_href' => '/content/locations/1/2/42',
            ],
            'priority' => '2',
            'hidden' => 'true',
            'remoteId' => 'remoteId12345678',
            'sortField' => 'PATH',
            'sortOrder' => 'ASC',
        ];

        $locationCreate = $this->getParser();
        $result = $locationCreate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            LocationCreateStruct::class,
            $result,
            'LocationCreateStruct not created correctly.'
        );

        $this->assertEquals(
            42,
            $result->parentLocationId,
            'LocationCreateStruct parentLocationId property not created correctly.'
        );

        $this->assertEquals(
            2,
            $result->priority,
            'LocationCreateStruct priority property not created correctly.'
        );

        $this->assertTrue(
            $result->hidden,
            'LocationCreateStruct hidden property not created correctly.'
        );

        $this->assertEquals(
            'remoteId12345678',
            $result->remoteId,
            'LocationCreateStruct remoteId property not created correctly.'
        );

        $this->assertEquals(
            Location::SORT_FIELD_PATH,
            $result->sortField,
            'LocationCreateStruct sortField property not created correctly.'
        );

        $this->assertEquals(
            Location::SORT_ORDER_ASC,
            $result->sortOrder,
            'LocationCreateStruct sortOrder property not created correctly.'
        );
    }

    /**
     * Test LocationCreate parser throwing exception on missing ParentLocation.
     */
    public function testParseExceptionOnMissingParentLocation()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing or invalid \'ParentLocation\' element for LocationCreate.');
        $inputArray = [
            'priority' => '0',
            'hidden' => 'false',
            'remoteId' => 'remoteId12345678',
            'sortField' => 'PATH',
            'sortOrder' => 'ASC',
        ];

        $locationCreate = $this->getParser();
        $locationCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test LocationCreate parser throwing exception on missing _href attribute for ParentLocation.
     */
    public function testParseExceptionOnMissingHrefAttribute()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'_href\' attribute for the ParentLocation element in LocationCreate.');
        $inputArray = [
            'ParentLocation' => [],
            'priority' => '0',
            'hidden' => 'false',
            'remoteId' => 'remoteId12345678',
            'sortField' => 'PATH',
            'sortOrder' => 'ASC',
        ];

        $locationCreate = $this->getParser();
        $locationCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test LocationCreate parser throwing exception on missing sort field.
     */
    public function testParseExceptionOnMissingSortField()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'sortField\' element for LocationCreate.');
        $inputArray = [
            'ParentLocation' => [
                '_href' => '/content/locations/1/2/42',
            ],
            'priority' => '0',
            'hidden' => 'false',
            'remoteId' => 'remoteId12345678',
            'sortOrder' => 'ASC',
        ];

        $locationCreate = $this->getParser();
        $locationCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test LocationCreate parser throwing exception on missing sort order.
     */
    public function testParseExceptionOnMissingSortOrder()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'sortOrder\' element for LocationCreate.');
        $inputArray = [
            'ParentLocation' => [
                '_href' => '/content/locations/1/2/42',
            ],
            'priority' => '0',
            'hidden' => 'false',
            'remoteId' => 'remoteId12345678',
            'sortField' => 'PATH',
        ];

        $locationCreate = $this->getParser();
        $locationCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the LocationCreateStruct parser.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\LocationCreate
     */
    protected function internalGetParser()
    {
        return new LocationCreate(
            $this->getLocationServiceMock(),
            $this->getParserTools()
        );
    }

    /**
     * Get the location service mock object.
     *
     * @return \eZ\Publish\API\Repository\LocationService
     */
    protected function getLocationServiceMock()
    {
        $locationServiceMock = $this->createMock(LocationService::class);

        $locationServiceMock->expects($this->any())
            ->method('newLocationCreateStruct')
            ->with($this->equalTo(42))
            ->willReturn(
                new LocationCreateStruct(['parentLocationId' => 42])
            );

        return $locationServiceMock;
    }

    public function getParseHrefExpectationsMap()
    {
        return [
            ['/content/locations/1/2/42', 'locationPath', '1/2/42'],
        ];
    }
}
