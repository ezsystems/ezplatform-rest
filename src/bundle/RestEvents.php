<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Bundle\Rest;

final class RestEvents
{
    /**
     * The REST_CSRF_TOKEN_VALIDATED event occurs after CSRF token has been validated as correct.
     */
    public const REST_CSRF_TOKEN_VALIDATED = 'ezpublish.rest.csrf_token_validated';
}

class_alias(RestEvents::class, 'EzSystems\EzPlatformRestBundle\RestEvents');
