<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Controller;

use Ibexa\Contracts\Rest\Exceptions\NotFoundException;
use Ibexa\Rest\Server\Controller as RestController;

/**
 * Root controller.
 */
class Root extends RestController
{
    /**
     * @var \Ibexa\Rest\Server\Service\RootResourceBuilderInterface
     */
    private $rootResourceBuilder;

    public function __construct($rootResourceBuilder)
    {
        $this->rootResourceBuilder = $rootResourceBuilder;
    }

    /**
     * List the root resources of the eZ Publish installation.
     *
     * @return \Ibexa\Rest\Values\Root
     */
    public function loadRootResource()
    {
        return $this->rootResourceBuilder->buildRootResource();
    }

    /**
     * Catch-all for REST requests.
     *
     * @throws \Ibexa\Contracts\Rest\Exceptions\NotFoundException
     */
    public function catchAll()
    {
        throw new NotFoundException('No such route');
    }
}

class_alias(Root::class, 'EzSystems\EzPlatformRest\Server\Controller\Root');
