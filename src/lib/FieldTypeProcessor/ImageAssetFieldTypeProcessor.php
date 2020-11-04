<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\FieldTypeProcessor;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use EzSystems\EzPlatformRest\FieldTypeProcessor;
use Symfony\Component\Routing\RouterInterface;

class ImageAssetFieldTypeProcessor extends FieldTypeProcessor
{
    /** @var \Symfony\Component\Routing\RouterInterface */
    private $router;

    /** @var \eZ\Publish\API\Repository\ContentService */
    private $contentService;

    /** @var string[] */
    private $configMappings;

    /** @var string[] */
    private $variations;

    public function __construct(
        RouterInterface $router,
        ContentService $contentService,
        array $configMappings,
        array $variations
    ) {
        $this->router = $router;
        $this->variations = $variations;
        $this->contentService = $contentService;
        $this->configMappings = $configMappings;
    }

    public function postProcessValueHash($outgoingValueHash)
    {
        if (!\is_array($outgoingValueHash)) {
            return $outgoingValueHash;
        }

        if ($outgoingValueHash['destinationContentId'] === null) {
            return $outgoingValueHash;
        }

        try {
            $imageContent = $this->contentService->loadContent((int) $outgoingValueHash['destinationContentId']);
        } catch (NotFoundException $e) {
            return $outgoingValueHash;
        }

        $field = $imageContent->getField($this->configMappings['content_field_identifier']);

        $imageId = $imageContent->id . '-' . $field->id . '-' . $imageContent->versionInfo->versionNo;
        foreach ($this->variations as $variationIdentifier) {
            $outgoingValueHash['variations'][$variationIdentifier] = [
                'href' => $this->router->generate(
                    'ezpublish_rest_binaryContent_getImageVariation',
                    [
                        'imageId' => $imageId,
                        'variationIdentifier' => $variationIdentifier,
                    ]
                ),
            ];
        }

        return $outgoingValueHash;
    }
}
