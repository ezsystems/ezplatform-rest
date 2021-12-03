<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Input\Parser\Limitation;

use Ibexa\Rest\Server\Input\Parser\Limitation\PathStringRouteBasedLimitationParser;
use Ibexa\Tests\Rest\Server\Input\Parser\BaseTest;

class PathStringRouteBasedLimitationParserTest extends BaseTest
{
    public function testParse()
    {
        $inputArray = [
            '_identifier' => 'Subtree',
            'values' => [
                'ref' => [
                    ['_href' => '/content/locations/1/2/3/4/'],
                ],
            ],
        ];

        $result = $this->getParser()->parse($inputArray, $this->getParsingDispatcherMock());

        self::assertInstanceOf('stdClass', $result);
        self::assertObjectHasAttribute('limitationValues', $result);
        self::assertArrayHasKey(0, $result->limitationValues);
        self::assertEquals('/1/2/3/4/', $result->limitationValues[0]);
    }

    /**
     * Must return the tested parser object.
     *
     * @return \Ibexa\Rest\Server\Input\Parser\Limitation\RouteBasedLimitationParser
     */
    protected function internalGetParser()
    {
        return new PathStringRouteBasedLimitationParser('pathString', 'stdClass');
    }

    public function getParseHrefExpectationsMap()
    {
        return [
            ['/content/locations/1/2/3/4/', 'pathString', '1/2/3/4/'],
        ];
    }
}

class_alias(PathStringRouteBasedLimitationParserTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\Limitation\PathStringRouteBasedLimitationParserTest');
