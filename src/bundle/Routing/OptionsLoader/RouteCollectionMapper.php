<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Bundle\Rest\Routing\OptionsLoader;

use Symfony\Component\Routing\RouteCollection;

/**
 * Maps a REST routes collection to the corresponding set of REST OPTIONS routes.
 *
 * Merges routes with the same path to a unique one, with the aggregate of merged methods in the _methods default.
 */
class RouteCollectionMapper
{
    /**
     * @var Mapper
     */
    protected $mapper;

    public function __construct(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * Iterates over $restRouteCollection, and returns the corresponding RouteCollection of OPTIONS REST routes.
     *
     * @param \Symfony\Component\Routing\RouteCollection $restRouteCollection
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function mapCollection(RouteCollection $restRouteCollection)
    {
        $optionsRouteCollection = new RouteCollection();

        foreach ($restRouteCollection->all() as $restRoute) {
            $optionsRouteName = $this->mapper->getOptionsRouteName($restRoute);

            $optionsRoute = $optionsRouteCollection->get($optionsRouteName);
            if ($optionsRoute === null) {
                $optionsRoute = $this->mapper->mapRoute($restRoute);
            } else {
                $optionsRoute = $this->mapper->mergeMethodsDefault($optionsRoute, $restRoute);
            }

            $optionsRouteCollection->add($optionsRouteName, $optionsRoute);
        }

        return $optionsRouteCollection;
    }
}

class_alias(RouteCollectionMapper::class, 'EzSystems\EzPlatformRestBundle\Routing\OptionsLoader\RouteCollectionMapper');
