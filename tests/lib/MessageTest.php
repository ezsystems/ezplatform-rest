<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests;

use EzSystems\EzPlatformRest;
use PHPUnit\Framework\TestCase;

/**
 * Tests for Message class.
 */
class MessageTest extends TestCase
{
    /**
     * Tests creating the message with default headers.
     */
    public function testCreateMessageDefaultHeaders()
    {
        $message = new EzPlatformRest\Message();

        $this->assertSame([], $message->headers);
    }

    /**
     * Tests creating the message with default body.
     */
    public function testCreateMessageDefaultBody()
    {
        $message = new EzPlatformRest\Message();

        $this->assertSame('', $message->body);
    }

    /**
     * Tests creating message with headers set through constructor.
     */
    public function testCreateMessageConstructorHeaders()
    {
        $message = new EzPlatformRest\Message(
            $headers = [
                'Content-Type' => 'text/xml',
            ]
        );

        $this->assertSame($headers, $message->headers);
    }

    /**
     * Tests creating message with body set through constructor.
     */
    public function testCreateMessageConstructorBody()
    {
        $message = new EzPlatformRest\Message(
            [],
            'Hello world!'
        );

        $this->assertSame('Hello world!', $message->body);
    }
}
