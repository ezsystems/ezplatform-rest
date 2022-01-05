<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Rest\Server\Input\Parser\Criterion;

use Ibexa\Contracts\Core\Repository\LocationService;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\Sibling as SiblingCriterion;
use Ibexa\Contracts\Rest\Exceptions\Parser as ParserExpcetion;
use Ibexa\Core\Repository\Values\Content\Location;
use Ibexa\Rest\Server\Input\Parser\Criterion\Sibling as SiblingParser;
use Ibexa\Tests\Rest\Server\Input\Parser\BaseTest;

final class SiblingTest extends BaseTest
{
    private const EXAMPLE_LOCATION_ID = 54;
    private const EXAMPLE_PARENT_LOCATION_ID = 2;

    /** @var \Ibexa\Contracts\Core\Repository\LocationService */
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
            'SiblingCriterion' => self::EXAMPLE_LOCATION_ID,
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
        /** Missing SiblingCriterion key */
        ], $this->getParsingDispatcherMock());
    }

    protected function internalGetParser(): SiblingParser
    {
        return new SiblingParser($this->locationService);
    }
}

class_alias(SiblingTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\Criterion\SiblingTest');
