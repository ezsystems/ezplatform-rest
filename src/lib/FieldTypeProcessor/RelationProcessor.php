<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\FieldTypeProcessor;

class RelationProcessor extends BaseRelationProcessor
{
    /**
     * In addition to the destinationContentId, adds a destinationContentHref entry.
     *
     * @param array $outgoingValueHash
     *
     * @return array
     */
    public function postProcessValueHash($outgoingValueHash)
    {
        if (!isset($outgoingValueHash['destinationContentId']) || !$this->canMapContentHref()) {
            return $outgoingValueHash;
        }

        $outgoingValueHash['destinationContentHref'] = $this->mapToContentHref(
            $outgoingValueHash['destinationContentId']
        );

        return $outgoingValueHash;
    }

    public function postProcessFieldSettingsHash($outgoingSettingsHash)
    {
        $outgoingSettingsHash = parent::postProcessFieldSettingsHash($outgoingSettingsHash);

        if (!empty($outgoingSettingsHash['selectionRoot'])) {
            $outgoingSettingsHash['selectionRootHref'] = $this->mapToLocationHref(
                $outgoingSettingsHash['selectionRoot']
            );
        }

        return $outgoingSettingsHash;
    }
}

class_alias(RelationProcessor::class, 'EzSystems\EzPlatformRest\FieldTypeProcessor\RelationProcessor');
