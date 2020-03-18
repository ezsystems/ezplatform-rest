<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use eZ\Publish\API\Repository\Values\User\Limitation\RoleLimitation;
use eZ\Publish\API\Repository\Values\User\Limitation\SectionLimitation;
use EzSystems\EzPlatformRest\Server\Input\Parser\RoleAssignInput;
use EzSystems\EzPlatformRest\Server\Values\RoleAssignment;
use EzSystems\EzPlatformRest\Exceptions\Parser;

class RoleAssignInputTest extends BaseTest
{
    /**
     * Tests the RoleAssignInput parser.
     */
    public function testParse()
    {
        $limitation = [
            '_identifier' => 'Section',
            'values' => [
                'ref' => [['_href' => '/content/sections/1']],
            ],
        ];

        $inputArray = [
            'Role' => ['_href' => '/user/roles/42'],
            'limitation' => $limitation,
        ];

        $this->getParsingDispatcherMock()
            ->expects($this->once())
            ->method('parse')
            ->with($limitation, 'application/vnd.ez.api.internal.limitation.Section')
            ->willReturn(new SectionLimitation());

        $roleAssignInput = $this->getParser();
        $result = $roleAssignInput->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            RoleAssignment::class,
            $result,
            'RoleAssignment not created correctly.'
        );

        $this->assertEquals(
            '42',
            $result->roleId,
            'RoleAssignment roleId property not created correctly.'
        );

        $this->assertInstanceOf(
            RoleLimitation::class,
            $result->limitation,
            'Limitation not created correctly.'
        );
    }

    /**
     * Test RoleAssignInput parser throwing exception on missing Role.
     */
    public function testParseExceptionOnMissingRole()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'Role\' element for RoleAssignInput.');
        $inputArray = [
            'limitation' => [
                '_identifier' => 'Section',
                'values' => [
                    'ref' => [
                        [
                            '_href' => '/content/sections/1',
                        ],
                        [
                            '_href' => '/content/sections/2',
                        ],
                        [
                            '_href' => '/content/sections/3',
                        ],
                    ],
                ],
            ],
        ];

        $roleAssignInput = $this->getParser();
        $roleAssignInput->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test RoleAssignInput parser throwing exception on invalid Role.
     */
    public function testParseExceptionOnInvalidRole()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Invalid \'Role\' element for RoleAssignInput.');
        $inputArray = [
            'Role' => [],
            'limitation' => [
                '_identifier' => 'Section',
                'values' => [
                    'ref' => [
                        [
                            '_href' => '/content/sections/1',
                        ],
                        [
                            '_href' => '/content/sections/2',
                        ],
                        [
                            '_href' => '/content/sections/3',
                        ],
                    ],
                ],
            ],
        ];

        $roleAssignInput = $this->getParser();
        $roleAssignInput->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test Limitation parser throwing exception on missing identifier.
     */
    public function testParseExceptionOnMissingLimitationIdentifier()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'_identifier\' attribute for Limitation.');
        $inputArray = [
            'Role' => [
                '_href' => '/user/roles/42',
            ],
            'limitation' => [
                'values' => [
                    'ref' => [
                        [
                            '_href' => '/content/sections/1',
                        ],
                        [
                            '_href' => '/content/sections/2',
                        ],
                        [
                            '_href' => '/content/sections/3',
                        ],
                    ],
                ],
            ],
        ];

        $roleAssignInput = $this->getParser();
        $roleAssignInput->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the role assign input parser.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\RoleAssignInput
     */
    protected function internalGetParser()
    {
        return new RoleAssignInput(
            $this->getParserTools()
        );
    }

    public function getParseHrefExpectationsMap()
    {
        return [
            ['/user/roles/42', 'roleId', 42],
        ];
    }
}
