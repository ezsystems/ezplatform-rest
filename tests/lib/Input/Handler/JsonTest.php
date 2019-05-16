<?php

/**
 * File containing the JsonTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Input\Handler;

use EzSystems\EzPlatformRest;
use PHPUnit\Framework\TestCase;

/**
 * Json input handler test.
 */
class JsonTest extends TestCase
{
    /**
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     */
    public function testConvertInvalidJson()
    {
        $handler = $this->getHandler();
        $handler->convert('{text:"Hello world!"}');
    }

    /**
     * Tests conversion of array to JSON.
     */
    public function testConvertJson()
    {
        $handler = $this->getHandler();

        $this->assertSame(
            array(
                'text' => 'Hello world!',
            ),
            $handler->convert('{"text":"Hello world!"}')
        );
    }

    public function testConvertFieldValue()
    {
        $handler = $this->getHandler();

        $this->assertSame(
            array(
                'Field' => array(
                    'fieldValue' => array(
                        array(
                            'id' => 1,
                            'name' => 'Joe Sindelfingen',
                            'email' => 'sindelfingen@example.com',
                        ),
                        array(
                            'id' => 2,
                            'name' => 'Joe Bielefeld',
                            'email' => 'bielefeld@example.com',
                        ),
                    ),
                ),
            ),
            $handler->convert(
                '{"Field":{"fieldValue":[{"id":1,"name":"Joe Sindelfingen","email":"sindelfingen@example.com"},{"id":2,"name":"Joe Bielefeld","email":"bielefeld@example.com"}]}}'
            )
        );
    }

    protected function getHandler()
    {
        return new EzPlatformRest\Input\Handler\Json();
    }
}
