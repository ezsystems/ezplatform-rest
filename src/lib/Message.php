<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest;

/**
 * Simple response struct.
 */
class Message
{
    /**
     * Response headers.
     *
     * @var array
     */
    public $headers;

    /**
     * Response body.
     *
     * @var string
     */
    public $body;

    /**
     * HTTP status code.
     *
     * @var int
     */
    public $statusCode;

    /**
     * Construct from headers and body.
     *
     * @param array $headers
     * @param string $body
     * @param int $statusCode
     */
    public function __construct(array $headers = [], $body = '', $statusCode = 200)
    {
        $this->headers = $headers;
        $this->body = $body;
        $this->statusCode = $statusCode;
    }
}

class_alias(Message::class, 'EzSystems\EzPlatformRest\Message');
