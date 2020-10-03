<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use EzSystems\EzPlatformRest\Exceptions\Parser;
use EzSystems\EzPlatformRest\Server\Input\Parser\JWTInput;
use EzSystems\EzPlatformRest\Server\Values\JWTInput as JWTInputValue;

class JWTInputTest extends BaseTest
{
    public function testParse(): void
    {
        $username = 'johndoe';
        $password = 'ibexa';

        $inputArray = [
            'username' => $username,
            'password' => $password,
        ];

        $jwtInput = $this->internalGetParser();
        $result = $jwtInput->parse($inputArray, $this->getParsingDispatcherMock());

        self::assertInstanceOf(
            JWTInputValue::class,
            $result,
            'JWTInput not created correctly.'
        );

        self::assertEquals(
            $username,
            $result->username
        );

        self::assertEquals(
            $password,
            $result->password
        );
    }

    public function testParseExceptionOnEmptyPassword(): void
    {
        $username = 'johndoe';

        $inputArray = [
            'username' => $username,
        ];

        $this->expectException(Parser::class);
        $this->expectExceptionMessage("Missing 'password' attribute for JWTInput.");

        $jwtInput = $this->internalGetParser();
        $jwtInput->parse($inputArray, $this->getParsingDispatcherMock());
    }

    public function testParseExceptionOnEmptyUsername(): void
    {
        $password = 'ibexa';

        $inputArray = [
            'password' => $password,
        ];

        $this->expectException(Parser::class);
        $this->expectExceptionMessage("Missing 'username' attribute for JWTInput.");

        $jwtInput = $this->internalGetParser();
        $jwtInput->parse($inputArray, $this->getParsingDispatcherMock());
    }

    protected function internalGetParser(): JWTInput
    {
        return new JWTInput();
    }
}
