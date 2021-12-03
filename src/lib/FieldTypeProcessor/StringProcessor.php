<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\FieldTypeProcessor;

use Ibexa\Contracts\Rest\FieldTypeProcessor;

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

class_alias(StringProcessor::class, 'EzSystems\EzPlatformRest\FieldTypeProcessor\StringProcessor');
