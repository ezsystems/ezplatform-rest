<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Bundle\Rest\ApiLoader;

use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Core\MVC\ConfigResolverInterface;
use Ibexa\Core\MVC\Symfony\RequestStackAware;
use Ibexa\Rest\FieldTypeProcessor\BinaryProcessor;
use Ibexa\Rest\FieldTypeProcessor\ImageAssetFieldTypeProcessor;
use Ibexa\Rest\FieldTypeProcessor\ImageProcessor;
use Ibexa\Rest\FieldTypeProcessor\MediaProcessor;
use Symfony\Component\Routing\RouterInterface;

class Factory
{
    use RequestStackAware;

    /**
     * @var \Ibexa\Core\MVC\ConfigResolverInterface
     */
    protected $configResolver;

    /**
     * @var \Ibexa\Contracts\Core\Repository\Repository
     */
    protected $repository;

    /**
     * @param \Ibexa\Core\MVC\ConfigResolverInterface $configResolver
     * @param \Ibexa\Contracts\Core\Repository\Repository $repository
     */
    public function __construct(ConfigResolverInterface $configResolver, Repository $repository)
    {
        $this->configResolver = $configResolver;
        $this->repository = $repository;
    }

    public function getBinaryFileFieldTypeProcessor()
    {
        $request = $this->getCurrentRequest();
        $hostPrefix = isset($request) ? rtrim($request->getUriForPath('/'), '/') : '';

        return new BinaryProcessor(sys_get_temp_dir(), $hostPrefix);
    }

    public function getMediaFieldTypeProcessor()
    {
        return new MediaProcessor(sys_get_temp_dir());
    }

    /**
     * Factory for ezpublish_rest.field_type_processor.ezimage.
     *
     * @param \Symfony\Component\Routing\RouterInterface $router
     *
     * @return \Ibexa\Rest\FieldTypeProcessor\ImageProcessor
     */
    public function getImageFieldTypeProcessor(RouterInterface $router)
    {
        $variationsIdentifiers = array_keys($this->configResolver->getParameter('image_variations'));
        sort($variationsIdentifiers);

        return new ImageProcessor(
            // Config for local temp dir
            // @todo get configuration
            sys_get_temp_dir(),
            // URL schema for image links
            // @todo get configuration
            $router,
            // Image variations (names only)
            $variationsIdentifiers
        );
    }

    public function getImageAssetFieldTypeProcessor(
        RouterInterface $router
    ): ImageAssetFieldTypeProcessor {
        $variationsIdentifiers = array_keys($this->configResolver->getParameter('image_variations'));
        sort($variationsIdentifiers);

        return new ImageAssetFieldTypeProcessor(
            $router,
            $this->repository->getContentService(),
            $this->configResolver->getParameter('fieldtypes.ezimageasset.mappings'),
            $variationsIdentifiers
        );
    }
}

class_alias(Factory::class, 'EzSystems\EzPlatformRestBundle\ApiLoader\Factory');
