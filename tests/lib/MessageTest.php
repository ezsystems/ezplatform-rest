<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest;

use Ibexa\Rest\Message;
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
        $message = new Message();

        $this->assertSame([], $message->headers);
    }

    /**
     * Tests creating the message with default body.
     */
    public function testCreateMessageDefaultBody()
    {
        $message = new Message();

        $this->assertSame('', $message->body);
    }

    /**
     * Tests creating message with headers set through constructor.
     */
    public function testCreateMessageConstructorHeaders()
    {
        $message = new Message(
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
        $message = new Message(
            [],
            'Hello world!'
        );

        $this->assertSame('Hello world!', $message->body);
    }
}

class_alias(MessageTest::class, 'EzSystems\EzPlatformRest\Tests\MessageTest');
