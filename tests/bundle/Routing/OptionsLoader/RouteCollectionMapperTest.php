<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Bundle\Rest\Routing\OptionsLoader;

use Ibexa\Bundle\Rest\Routing\OptionsLoader\Mapper;
use Ibexa\Bundle\Rest\Routing\OptionsLoader\RouteCollectionMapper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * @covers \EzSystems\EzPlatformRestBundle\Routing\OptionsLoader\RouteCollectionMapper
 */
class RouteCollectionMapperTest extends TestCase
{
    /** @var \Ibexa\Bundle\Rest\Routing\OptionsLoader\RouteCollectionMapper */
    protected $collectionMapper;

    public function setUp(): void
    {
        $this->collectionMapper = new RouteCollectionMapper(
            new Mapper()
        );
    }

    public function testAddRestRoutesCollection()
    {
        $restRoutesCollection = new RouteCollection();
        $restRoutesCollection->add('ezpublish_rest_route_one_get', $this->createRoute('/route/one', ['GET']));
        $restRoutesCollection->add('ezpublish_rest_route_one_post', $this->createRoute('/route/one', ['POST']));
        $restRoutesCollection->add('ezpublish_rest_route_two_delete', $this->createRoute('/route/two', ['DELETE']));

        $optionsRouteCollection = $this->collectionMapper->mapCollection($restRoutesCollection);

        self::assertEquals(
            2,
            $optionsRouteCollection->count()
        );

        self::assertInstanceOf(
            Route::class,
            $optionsRouteCollection->get('ezpublish_rest_options_route_one')
        );

        self::assertInstanceOf(
            Route::class,
            $optionsRouteCollection->get('ezpublish_rest_options_route_two')
        );

        self::assertEquals(
            'GET,POST',
            $optionsRouteCollection->get('ezpublish_rest_options_route_one')->getDefault('allowedMethods')
        );

        self::assertEquals(
            'DELETE',
            $optionsRouteCollection->get('ezpublish_rest_options_route_two')->getDefault('allowedMethods')
        );
    }

    /**
     * @param string $path
     * @param array $methods
     *
     * @return \Symfony\Component\Routing\Route
     */
    private function createRoute($path, array $methods)
    {
        return new Route($path, [], [], [], '', [], $methods);
    }
}

class_alias(RouteCollectionMapperTest::class, 'EzSystems\EzPlatformRestBundle\Tests\Routing\OptionsLoader\RouteCollectionMapperTest');
