<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser\Criterion;

use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Sibling as SiblingCriterion;
use eZ\Publish\Core\Repository\Values\Content\Location;
use EzSystems\EzPlatformRest\Exceptions\Parser as ParserExpcetion;
use EzSystems\EzPlatformRest\Server\Input\Parser\Criterion\Sibling as SiblingParser;
use EzSystems\EzPlatformRest\Tests\Server\Input\Parser\BaseTest;

final class SiblingTest extends BaseTest
{
    private const EXAMPLE_LOCATION_ID = 54;
    private const EXAMPLE_PARENT_LOCATION_ID = 2;

    /** @var \eZ\Publish\API\Repository\LocationService */
    private $locationService;

    protected function setUp(): void
    {
        $this->locationService = $this->createMock(LocationService::class);
    }

    public function testParse(): void
    {
        $location = new Location([
            'id' => self::EXAMPLE_LOCATION_ID,
            'parentLocationId' => self::EXAMPLE_PARENT_LOCATION_ID,
        ]);

        $this->locationService
            ->method('loadLocation')
            ->with(self::EXAMPLE_LOCATION_ID)
            ->willReturn($location);

        $actual = $this->getParser()->parse([
            'SiblingsCriterion' => self::EXAMPLE_LOCATION_ID,
        ], $this->getParsingDispatcherMock());

        $exepected = new SiblingCriterion(
            self::EXAMPLE_LOCATION_ID,
            self::EXAMPLE_PARENT_LOCATION_ID
        );

        $this->assertEquals($exepected, $actual);
    }

    public function testParseThrowsParserException(): void
    {
        $this->expectException(ParserExpcetion::class);
        $this->expectExceptionMessage('Invalid <SiblingCriterion> format');

        $this->getParser()->parse([
        /** Missing SiblingsCriterion key */
        ], $this->getParsingDispatcherMock());
    }

    protected function internalGetParser(): SiblingParser
    {
        return new SiblingParser($this->locationService);
    }
}
