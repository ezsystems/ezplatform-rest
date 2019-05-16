<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\FieldTypeProcessor;

use EzSystems\EzPlatformRest\FieldTypeProcessor;

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
