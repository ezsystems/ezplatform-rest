<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\FieldTypeProcessor;

class RelationListProcessor extends BaseRelationProcessor
{
    /**
     * In addition to the list of destinationContentIds, adds a destinationContentHrefs
     * array, with matching content uris.
     *
     * @param array $outgoingValueHash
     *
     * @return array
     */
    public function postProcessValueHash($outgoingValueHash)
    {
        if (
            !isset($outgoingValueHash['destinationContentIds']) ||
            !is_array($outgoingValueHash['destinationContentIds']) ||
            !$this->canMapContentHref()
        ) {
            return $outgoingValueHash;
        }

        $outgoingValueHash['destinationContentHrefs'] = array_map(
            function ($contentId) {
                return $this->mapToContentHref($contentId);
            },
            $outgoingValueHash['destinationContentIds']
        );

        return $outgoingValueHash;
    }

    public function postProcessFieldSettingsHash($outgoingSettingsHash)
    {
        $outgoingSettingsHash = parent::postProcessFieldSettingsHash($outgoingSettingsHash);

        if (!empty($outgoingSettingsHash['selectionDefaultLocation'])) {
            $outgoingSettingsHash['selectionDefaultLocationHref'] = $this->mapToLocationHref(
                $outgoingSettingsHash['selectionDefaultLocation']
            );
        }

        return $outgoingSettingsHash;
    }
}

class_alias(RelationListProcessor::class, 'EzSystems\EzPlatformRest\FieldTypeProcessor\RelationListProcessor');
