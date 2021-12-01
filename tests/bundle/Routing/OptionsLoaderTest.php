<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Bundle\Rest\Routing;

use Ibexa\Bundle\Rest\Routing\OptionsLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouteCollection;

/**
 * @covers \EzSystems\EzPlatformRestBundle\Routing\OptionsLoader
 */
class OptionsLoaderTest extends TestCase
{
    /**
     * @param string $type
     * @param bool $expected
     * @dataProvider getResourceType
     */
    public function testSupportsResourceType($type, $expected)
    {
        self::assertEquals(
            $expected,
            $this->getOptionsLoader()->supports(null, $type)
        );
    }

    public function getResourceType()
    {
        return [
            ['rest_options', true],
            ['something else', false],
        ];
    }

    public function testLoad()
    {
        $optionsRouteCollection = new RouteCollection();

        $this->getRouteCollectionMapperMock()->expects($this->once())
            ->method('mapCollection')
            ->with(new RouteCollection())
            ->willReturn($optionsRouteCollection);

        self::assertSame(
            $optionsRouteCollection,
            $this->getOptionsLoader()->load('resource', 'rest_options')
        );
    }

    /**
     * Returns a partially mocked OptionsLoader, with the import method mocked.
     *
     * @return \Ibexa\Bundle\Rest\Routing\OptionsLoader|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getOptionsLoader()
    {
        $mock = $this->getMockBuilder(OptionsLoader::class)
            ->setConstructorArgs([$this->getRouteCollectionMapperMock()])
            ->setMethods(['import'])
            ->getMock();

        $mock->expects($this->any())
            ->method('import')
            ->with($this->anything(), $this->anything())
            ->willReturn(new RouteCollection());

        return $mock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getRouteCollectionMapperMock()
    {
        if (!isset($this->routeCollectionMapperMock)) {
            $this->routeCollectionMapperMock = $this->createMock(OptionsLoader\RouteCollectionMapper::class);
        }

        return $this->routeCollectionMapperMock;
    }
}

class_alias(OptionsLoaderTest::class, 'EzSystems\EzPlatformRestBundle\Tests\Routing\OptionsLoaderTest');
