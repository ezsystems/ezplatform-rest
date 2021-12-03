<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\FieldTypeProcessor;

use Ibexa\Contracts\Rest\FieldTypeProcessor;
use Ibexa\Core\FieldType\DateAndTime\Type;

class DateAndTimeProcessor extends FieldTypeProcessor
{
    /**
     * {@inheritdoc}
     */
    public function preProcessFieldSettingsHash($incomingSettingsHash)
    {
        if (isset($incomingSettingsHash['defaultType'])) {
            switch ($incomingSettingsHash['defaultType']) {
                case 'DEFAULT_EMPTY':
                    $incomingSettingsHash['defaultType'] = Type::DEFAULT_EMPTY;
                    break;
                case 'DEFAULT_CURRENT_DATE':
                    $incomingSettingsHash['defaultType'] = Type::DEFAULT_CURRENT_DATE;
                    break;
                case 'DEFAULT_CURRENT_DATE_ADJUSTED':
                    $incomingSettingsHash['defaultType'] = Type::DEFAULT_CURRENT_DATE_ADJUSTED;
            }
        }

        return $incomingSettingsHash;
    }

    /**
     * {@inheritdoc}
     */
    public function postProcessFieldSettingsHash($outgoingSettingsHash)
    {
        if (isset($outgoingSettingsHash['defaultType'])) {
            switch ($outgoingSettingsHash['defaultType']) {
                case Type::DEFAULT_EMPTY:
                    $outgoingSettingsHash['defaultType'] = 'DEFAULT_EMPTY';
                    break;
                case Type::DEFAULT_CURRENT_DATE:
                    $outgoingSettingsHash['defaultType'] = 'DEFAULT_CURRENT_DATE';
                    break;
                case Type::DEFAULT_CURRENT_DATE_ADJUSTED:
                    $outgoingSettingsHash['defaultType'] = 'DEFAULT_CURRENT_DATE_ADJUSTED';
            }
        }

        return $outgoingSettingsHash;
    }
}

class_alias(DateAndTimeProcessor::class, 'EzSystems\EzPlatformRest\FieldTypeProcessor\DateAndTimeProcessor');
