<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Bundle\Rest\Routing;

use Ibexa\Bundle\Rest\Routing\OptionsLoader\RouteCollectionMapper;
use Symfony\Component\Config\Loader\Loader;

/**
 * Goes through all REST routes, and registers new routes for all routes
 * a new one with the OPTIONS method.
 */
class OptionsLoader extends Loader
{
    /** @var RouteCollectionMapperMapper */
    protected $routeCollectionMapper;

    public function __construct(RouteCollectionMapper $mapper)
    {
        $this->routeCollectionMapper = $mapper;
    }

    /**
     * @param mixed $resource
     * @param string $type
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function load($resource, $type = null)
    {
        return $this->routeCollectionMapper->mapCollection($this->import($resource));
    }

    public function supports($resource, $type = null)
    {
        return $type === 'rest_options';
    }
}

class_alias(OptionsLoader::class, 'EzSystems\EzPlatformRestBundle\Routing\OptionsLoader');
