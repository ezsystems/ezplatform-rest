<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\FieldTypeProcessor;

use Ibexa\Contracts\Rest\FieldTypeProcessor;

class FloatProcessor extends FieldTypeProcessor
{
    /**
     * {@inheritdoc}
     */
    public function preProcessValueHash($incomingValueHash)
    {
        if (is_numeric($incomingValueHash)) {
            $incomingValueHash = (float)$incomingValueHash;
        }

        return $incomingValueHash;
    }
}

class_alias(FloatProcessor::class, 'EzSystems\EzPlatformRest\FieldTypeProcessor\FloatProcessor');
