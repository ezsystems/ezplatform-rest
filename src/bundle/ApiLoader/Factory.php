<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Bundle\Rest\ApiLoader;

use eZ\Publish\Core\MVC\ConfigResolverInterface;
use eZ\Publish\Core\MVC\Symfony\RequestStackAware;
use eZ\Publish\API\Repository\Repository;
use Ibexa\Rest\FieldTypeProcessor\BinaryProcessor;
use Ibexa\Rest\FieldTypeProcessor\ImageAssetFieldTypeProcessor;
use Ibexa\Rest\FieldTypeProcessor\ImageProcessor;
use Ibexa\Rest\FieldTypeProcessor\MediaProcessor;
use Symfony\Component\Routing\RouterInterface;

class Factory
{
    use RequestStackAware;

    /**
     * @var \eZ\Publish\Core\MVC\ConfigResolverInterface
     */
    protected $configResolver;

    /**
     * @var \eZ\Publish\API\Repository\Repository
     */
    protected $repository;

    /**
     * @param \eZ\Publish\Core\MVC\ConfigResolverInterface $configResolver
     * @param \eZ\Publish\API\Repository\Repository $repository
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
     * @return \EzSystems\EzPlatformRest\FieldTypeProcessor\ImageProcessor
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
