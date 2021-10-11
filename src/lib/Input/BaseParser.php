<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Input;

use Ibexa\Contracts\Rest\Input\Parser;
use Ibexa\Rest\RequestParser;

abstract class BaseParser extends Parser
{
    /**
     * URL handler.
     *
     * @var \EzSystems\EzPlatformRest\RequestParser
     */
    protected $requestParser;

    public function setRequestParser(RequestParser $requestParser)
    {
        $this->requestParser = $requestParser;
    }
}

class_alias(BaseParser::class, 'EzSystems\EzPlatformRest\Input\BaseParser');
