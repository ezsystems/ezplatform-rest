<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Input\Parser;

use Ibexa\Contracts\Rest\Exceptions\Parser;
use Ibexa\Rest\Server\Input\Parser\SessionInput;
use Ibexa\Rest\Server\Values\SessionInput as SessionInputValue;

class SessionInputTest extends BaseTest
{
    /**
     * Tests the SessionInput parser.
     */
    public function testParse()
    {
        $inputArray = [
            'login' => 'Login Foo',
            'password' => 'Password Bar',
        ];

        $sessionInput = $this->getParser();
        $result = $sessionInput->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertEquals(
            new SessionInputValue($inputArray),
            $result,
            'SessionInput not created correctly.'
        );
    }

    /**
     * Test SessionInput parser throwing exception on missing password.
     */
    public function testParseExceptionOnMissingIdentifier()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'password\' attribute for SessionInput.');
        $inputArray = [
            'login' => 'Login Foo',
        ];

        $sessionInput = $this->getParser();
        $sessionInput->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test SessionInput parser throwing exception on missing login.
     */
    public function testParseExceptionOnMissingName()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'login\' attribute for SessionInput.');
        $inputArray = [
            'password' => 'Password Bar',
        ];

        $sessionInput = $this->getParser();
        $sessionInput->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the session input parser.
     *
     * @return \Ibexa\Rest\Server\Input\Parser\SessionInput
     */
    protected function internalGetParser()
    {
        return new SessionInput();
    }
}

class_alias(SessionInputTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\SessionInputTest');
