<?php

/**
 * File containing the StringProcessor class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\FieldTypeProcessor;

use EzSystems\EzPlatformRest\FieldTypeProcessor;

class StringProcessor extends FieldTypeProcessor
{
    /**
     * {@inheritdoc}
     */
    public function preProcessValueHash($incomingValueHash)
    {
        return (string) $incomingValueHash;
    }
}
