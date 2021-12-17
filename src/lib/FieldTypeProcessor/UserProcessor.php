<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\FieldTypeProcessor;

use Ibexa\Contracts\Rest\FieldTypeProcessor;

class UserProcessor extends FieldTypeProcessor
{
    public function preProcessValueHash($incomingValueHash)
    {
        // For BC with usage in Platform UI 1.x
        if (isset($incomingValueHash['password'])) {
            $incomingValueHash['passwordHash'] = $incomingValueHash['password'];
            unset($incomingValueHash['password']);
        }

        return $incomingValueHash;
    }

    public function postProcessValueHash($outgoingValueHash)
    {
        unset($outgoingValueHash['passwordHash'], $outgoingValueHash['passwordHashType']);

        return $outgoingValueHash;
    }
}

class_alias(UserProcessor::class, 'EzSystems\EzPlatformRest\FieldTypeProcessor\UserProcessor');
