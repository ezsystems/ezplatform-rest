<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Controller;

use eZ\Publish\API\Repository\Exceptions\InvalidVariationException;
use eZ\Publish\Core\FieldType\Image\Value as ImageValue;
use eZ\Publish\Core\FieldType\ImageAsset\Value as ImageAssetValue;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use eZ\Publish\SPI\Variation\VariationHandler;
use EzSystems\EzPlatformRest\Exceptions;
use EzSystems\EzPlatformRest\Server\Controller as RestController;
use EzSystems\EzPlatformRest\Server\Values\CachedValue;

/**
 * Binary content controller.
 */
class BinaryContent extends RestController
{
    /** @var \eZ\Publish\SPI\Variation\VariationHandler */
    protected $imageVariationHandler;

    /** @var \eZ\Publish\Core\MVC\ConfigResolverInterface */
    private $configResolver;

    public function __construct(
        VariationHandler $imageVariationHandler,
        ConfigResolverInterface $configResolver
    ) {
        $this->imageVariationHandler = $imageVariationHandler;
        $this->configResolver = $configResolver;
    }

    /**
     * Returns data about the image variation $variationIdentifier of image field $fieldId.
     * Will generate the alias if it hasn't been generated yet.
     *
     * @param mixed  $imageId A custom ID that identifies the image field.
     *                        Until v6.9, the format is {contentId}-{fieldId}.
     *                        since v6.9, the format is {contentId}-{fieldId}-{versionNumber}.
     *                        If the version number isn't specified, the default one is used.
     * @param string $variationIdentifier
     *
     * @throws \EzSystems\EzPlatformRest\Exceptions\NotFoundException
     *
     * @return \eZ\Publish\SPI\Variation\Values\Variation
     */
    public function getImageVariation($imageId, $variationIdentifier)
    {
        list($contentId, $fieldId, $versionNumber) = $this->parseImageId($imageId);
        $content = $this->repository->getContentService()->loadContent($contentId, null, $versionNumber);

        $fieldFound = false;
        /** @var $field \eZ\Publish\API\Repository\Values\Content\Field */
        foreach ($content->getFields() as $field) {
            if ($field->id === $fieldId) {
                $fieldFound = true;
                break;
            }
        }

        if (!$fieldFound) {
            throw new Exceptions\NotFoundException("No image Field with ID $fieldId found");
        }

        $value = $field->value;

        if ($value instanceof ImageValue && $value->uri === null) {
            throw new Exceptions\NotFoundException("Image file {$value->id} doesn't exist");
        }
        if ($value instanceof ImageAssetValue) {
            if ($value->destinationContentId === null) {
                throw new Exceptions\NotFoundException("There is no image connected with field {$fieldId}");
            }
            $content = $this->repository->getContentService()->loadContent((int) $value->destinationContentId);
            $mappings = $this->configResolver->getParameter('fieldtypes.ezimageasset.mappings');
            $field = $content->getField($mappings['content_field_identifier']);
        }

        try {
            $variation = $this->imageVariationHandler->getVariation($field, $content->getVersionInfo(), $variationIdentifier);

            if ($content->contentInfo->mainLocationId === null || $versionNumber !== $content->contentInfo->currentVersionNo) {
                return $variation;
            }

            return new CachedValue(
                $variation,
                ['locationId' => $content->contentInfo->mainLocationId]
            );
        } catch (InvalidVariationException $e) {
            throw new Exceptions\NotFoundException("Invalid image variation $variationIdentifier");
        }
    }

    /**
     * Parses an imageId string into contentId, fieldId and versionNumber.
     *
     * @param string $imageId Either {contentId}-{fieldId} or {contentId}-{fieldId}-{versionNumber}
     *
     * @return array An array with 3 keys: contentId, fieldId and versionNumber.
     *               If the versionNumber wasn't set, it is returned as null.
     *
     * @throws \EzSystems\EzPlatformRest\Exceptions\NotFoundException If the imageId format is invalid
     */
    private function parseImageId($imageId)
    {
        $idArray = explode('-', $imageId);
        $idArray = array_map('intval', $idArray);

        if (\count($idArray) == 2) {
            return array_merge($idArray, [null]);
        } elseif (\count($idArray) == 3) {
            return $idArray;
        }

        throw new Exceptions\NotFoundException("Invalid image ID {$imageId}");
    }
}
