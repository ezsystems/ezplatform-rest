<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use eZ\Publish\Core\Repository\LocationService;
use EzSystems\EzPlatformRest\Server\Input\Parser\LocationUpdate;
use eZ\Publish\API\Repository\Values\Content\LocationUpdateStruct;
use eZ\Publish\API\Repository\Values\Content\Location;
use EzSystems\EzPlatformRest\Server\Values\RestLocationUpdateStruct;
use EzSystems\EzPlatformRest\Exceptions\Parser;

class LocationUpdateTest extends BaseTest
{
    /**
     * Tests the LocationUpdate parser.
     */
    public function testParse()
    {
        $inputArray = [
            'priority' => 0,
            'remoteId' => 'remote-id',
            'hidden' => 'true',
            'sortField' => 'PATH',
            'sortOrder' => 'ASC',
        ];

        $locationUpdate = $this->getParser();
        $result = $locationUpdate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            RestLocationUpdateStruct::class,
            $result,
            'LocationUpdateStruct not created correctly.'
        );

        $this->assertInstanceOf(
            LocationUpdateStruct::class,
            $result->locationUpdateStruct,
            'LocationUpdateStruct not created correctly.'
        );

        $this->assertEquals(
            0,
            $result->locationUpdateStruct->priority,
            'LocationUpdateStruct priority property not created correctly.'
        );

        $this->assertEquals(
            'remote-id',
            $result->locationUpdateStruct->remoteId,
            'LocationUpdateStruct remoteId property not created correctly.'
        );

        $this->assertTrue(
            $result->hidden,
            'hidden property not created correctly.'
        );

        $this->assertEquals(
            Location::SORT_FIELD_PATH,
            $result->locationUpdateStruct->sortField,
            'LocationUpdateStruct sortField property not created correctly.'
        );

        $this->assertEquals(
            Location::SORT_ORDER_ASC,
            $result->locationUpdateStruct->sortOrder,
            'LocationUpdateStruct sortOrder property not created correctly.'
        );
    }

    /**
     * Test LocationUpdate parser throwing exception on missing sort field.
     */
    public function testParseExceptionOnMissingSortField()
    {
        $this->expectException('EzSystems\EzPlatformRest\Exceptions\Parser');
        $this->expectExceptionMessage('Missing \'sortField\' element for LocationUpdate.');
        $inputArray = [
            'priority' => 0,
            'remoteId' => 'remote-id',
            'sortOrder' => 'ASC',
        ];

        $locationUpdate = $this->getParser();
        $locationUpdate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test LocationUpdate parser throwing exception on missing sort order.
     */
    public function testParseExceptionOnMissingSortOrder()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'sortOrder\' element for LocationUpdate.');
        $inputArray = [
            'priority' => 0,
            'remoteId' => 'remote-id',
            'sortField' => 'PATH',
        ];

        $locationUpdate = $this->getParser();
        $locationUpdate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the LocationUpdateStruct parser.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\LocationUpdate
     */
    protected function internalGetParser()
    {
        return new LocationUpdate(
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
            ->method('newLocationUpdateStruct')
            ->willReturn(
                new LocationUpdateStruct()
            );

        return $locationServiceMock;
    }
}
