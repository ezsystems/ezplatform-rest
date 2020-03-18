<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use EzSystems\EzPlatformRest\Server\Input\Parser\SessionInput;
use EzSystems\EzPlatformRest\Server\Values\SessionInput as SessionInputValue;
use EzSystems\EzPlatformRest\Exceptions\Parser;

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
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\SessionInput
     */
    protected function internalGetParser()
    {
        return new SessionInput();
    }
}
