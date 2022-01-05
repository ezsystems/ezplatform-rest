<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Bundle\Rest\Routing\OptionsLoader;

use Symfony\Component\Routing\Route;

/**
 * Maps a standard REST route to its OPTIONS equivalent.
 */
class Mapper
{
    /**
     * @param $route Route REST route
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function mapRoute(Route $route)
    {
        $optionsRoute = clone $route;
        $optionsRoute->setMethods(['OPTIONS']);
        $optionsRoute->setDefault(
            '_controller',
            'ezpublish_rest.controller.options:getRouteOptions'
        );

        $optionsRoute->setDefault(
            'allowedMethods',
            implode(',', $route->getMethods())
        );

        return $optionsRoute;
    }

    /**
     * Merges the methods from $restRoute into the _method default of $optionsRoute.
     *
     * @param \Symfony\Component\Routing\Route $restRoute
     * @param \Symfony\Component\Routing\Route $optionsRoute
     *
     * @return \Symfony\Component\Routing\Route $optionsRoute with the methods from $restRoute in the _methods default
     */
    public function mergeMethodsDefault(Route $optionsRoute, Route $restRoute)
    {
        $mergedRoute = clone $optionsRoute;
        $mergedRoute->setDefault(
            'allowedMethods',
            implode(
                ',',
                array_unique(
                    array_merge(
                        explode(',', $optionsRoute->getDefault('allowedMethods')),
                        $restRoute->getMethods()
                    )
                )
            )
        );

        return $mergedRoute;
    }

    /**
     * Returns the OPTIONS name of a REST route.
     *
     * @param $route Route
     *
     * @return string
     */
    public function getOptionsRouteName(Route $route)
    {
        $name = str_replace('/', '_', $route->getPath());

        return 'ezpublish_rest_options_' . trim($name, '_');
    }
}

class_alias(Mapper::class, 'EzSystems\EzPlatformRestBundle\Routing\OptionsLoader\Mapper');
