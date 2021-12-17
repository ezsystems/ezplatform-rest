<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\Values\Content\Location;
use Ibexa\Contracts\Core\Repository\Values\Content\LocationUpdateStruct;
use Ibexa\Contracts\Rest\Exceptions\Parser;
use Ibexa\Core\Repository\LocationService;
use Ibexa\Rest\Server\Input\Parser\LocationUpdate;
use Ibexa\Rest\Server\Values\RestLocationUpdateStruct;

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
     * Test LocationUpdate parser with missing sort field.
     */
    public function testParseWithMissingSortField()
    {
        $inputArray = [
            'priority' => 0,
            'remoteId' => 'remote-id',
            'sortOrder' => 'ASC',
        ];

        $locationUpdate = $this->getParser();
        $result = $locationUpdate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            RestLocationUpdateStruct::class,
            $result
        );

        $this->assertInstanceOf(
            LocationUpdateStruct::class,
            $result->locationUpdateStruct
        );

        $this->assertNull(
            $result->locationUpdateStruct->sortField
        );
    }

    /**
     * Test LocationUpdate parser with missing sort order.
     */
    public function testParseWithMissingSortOrder()
    {
        $inputArray = [
            'priority' => 0,
            'remoteId' => 'remote-id',
            'sortField' => 'PATH',
        ];

        $locationUpdate = $this->getParser();
        $result = $locationUpdate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            RestLocationUpdateStruct::class,
            $result
        );

        $this->assertInstanceOf(
            LocationUpdateStruct::class,
            $result->locationUpdateStruct
        );

        $this->assertNull(
            $result->locationUpdateStruct->sortOrder
        );
    }

    /**
     * Returns the LocationUpdateStruct parser.
     *
     * @return \Ibexa\Rest\Server\Input\Parser\LocationUpdate
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
     * @return \Ibexa\Contracts\Core\Repository\LocationService
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

class_alias(LocationUpdateTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\LocationUpdateTest');
