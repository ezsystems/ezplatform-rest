<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use EzSystems\EzPlatformRest\Server\Input\Parser\SessionInput;
use EzSystems\EzPlatformRest\Server\Values\SessionInput as SessionInputValue;

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
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Missing 'password' attribute for SessionInput.
     */
    public function testParseExceptionOnMissingIdentifier()
    {
        $inputArray = [
            'login' => 'Login Foo',
        ];

        $sessionInput = $this->getParser();
        $sessionInput->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test SessionInput parser throwing exception on missing login.
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Missing 'login' attribute for SessionInput.
     */
    public function testParseExceptionOnMissingName()
    {
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
