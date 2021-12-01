<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\FieldTypeProcessor;

use Ibexa\Contracts\Core\Repository\LocationService;
use Ibexa\Contracts\Rest\FieldTypeProcessor;
use Ibexa\Core\FieldType\Relation\Type;
use Symfony\Component\Routing\RouterInterface;

abstract class BaseRelationProcessor extends FieldTypeProcessor
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @var \Ibexa\Contracts\Core\Repository\LocationService
     */
    private $locationService;

    /**
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param \Ibexa\Contracts\Core\Repository\LocationService $locationService
     */
    public function setLocationService(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * @return bool
     */
    public function canMapContentHref()
    {
        return isset($this->router);
    }

    /**
     * @param  int $contentId
     *
     * @return string
     */
    public function mapToContentHref($contentId)
    {
        return $this->router->generate('ezpublish_rest_loadContent', ['contentId' => $contentId]);
    }

    /**
     * @param  int $locationId
     *
     * @return string
     */
    public function mapToLocationHref($locationId)
    {
        return $this->router->generate('ezpublish_rest_loadLocation', [
            'locationPath' => implode('/', $this->locationService->loadLocation($locationId)->path),
        ]);
    }

    public function preProcessFieldSettingsHash($incomingSettingsHash)
    {
        if (isset($incomingSettingsHash['selectionMethod'])) {
            switch ($incomingSettingsHash['selectionMethod']) {
                case 'SELECTION_BROWSE':
                    $incomingSettingsHash['selectionMethod'] = Type::SELECTION_BROWSE;
                    break;
                case 'SELECTION_DROPDOWN':
                    $incomingSettingsHash['selectionMethod'] = Type::SELECTION_DROPDOWN;
            }
        }

        return $incomingSettingsHash;
    }

    public function postProcessFieldSettingsHash($outgoingSettingsHash)
    {
        if (isset($outgoingSettingsHash['selectionMethod'])) {
            switch ($outgoingSettingsHash['selectionMethod']) {
                case Type::SELECTION_BROWSE:
                    $outgoingSettingsHash['selectionMethod'] = 'SELECTION_BROWSE';
                    break;
                case Type::SELECTION_DROPDOWN:
                    $outgoingSettingsHash['selectionMethod'] = 'SELECTION_DROPDOWN';
            }
        }

        return $outgoingSettingsHash;
    }
}

class_alias(BaseRelationProcessor::class, 'EzSystems\EzPlatformRest\FieldTypeProcessor\BaseRelationProcessor');
