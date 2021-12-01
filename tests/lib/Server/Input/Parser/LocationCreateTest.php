<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\Values\Content\Location;
use Ibexa\Contracts\Core\Repository\Values\Content\LocationCreateStruct;
use Ibexa\Contracts\Rest\Exceptions\Parser;
use Ibexa\Core\Repository\LocationService;
use Ibexa\Rest\Server\Input\Parser\LocationCreate;

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
     * @return \Ibexa\Rest\Server\Input\Parser\LocationCreate
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
     * @return \Ibexa\Contracts\Core\Repository\LocationService
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

class_alias(LocationCreateTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\LocationCreateTest');
