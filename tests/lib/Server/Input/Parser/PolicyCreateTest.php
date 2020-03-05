<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use eZ\Publish\API\Repository\Values\User\Limitation;
use eZ\Publish\Core\Repository\RoleService;
use EzSystems\EzPlatformRest\Server\Input\Parser\PolicyCreate;
use eZ\Publish\Core\Repository\Values\User\PolicyCreateStruct;
use EzSystems\EzPlatformRest\Exceptions\Parser;

class PolicyCreateTest extends BaseTest
{
    /**
     * Tests the PolicyCreate parser.
     */
    public function testParse()
    {
        $inputArray = [
            'module' => 'content',
            'function' => 'delete',
            'limitations' => [
                'limitation' => [
                    [
                        '_identifier' => 'Class',
                        'values' => [
                            'ref' => [
                                [
                                    '_href' => 1,
                                ],
                                [
                                    '_href' => 2,
                                ],
                                [
                                    '_href' => 3,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $policyCreate = $this->getParser();
        $result = $policyCreate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            PolicyCreateStruct::class,
            $result,
            'PolicyCreateStruct not created correctly.'
        );

        $this->assertEquals(
            'content',
            $result->module,
            'PolicyCreateStruct module property not created correctly.'
        );

        $this->assertEquals(
            'delete',
            $result->function,
            'PolicyCreateStruct function property not created correctly.'
        );

        $parsedLimitations = $result->getLimitations();

        $this->assertIsArray($parsedLimitations, 'PolicyCreateStruct limitations not created correctly');

        $this->assertCount(
            1,
            $parsedLimitations,
            'PolicyCreateStruct limitations not created correctly'
        );

        $this->assertInstanceOf(
            Limitation::class,
            $parsedLimitations['Class'],
            'Limitation not created correctly.'
        );

        $this->assertEquals(
            'Class',
            $parsedLimitations['Class']->getIdentifier(),
            'Limitation identifier not created correctly.'
        );

        $this->assertEquals(
            [1, 2, 3],
            $parsedLimitations['Class']->limitationValues,
            'Limitation values not created correctly.'
        );
    }

    /**
     * Test PolicyCreate parser throwing exception on missing module.
     */
    public function testParseExceptionOnMissingModule()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'module\' attribute for PolicyCreate.');
        $inputArray = [
            'function' => 'delete',
            'limitations' => [
                'limitation' => [
                    [
                        '_identifier' => 'Class',
                        'values' => [
                            'ref' => [
                                [
                                    '_href' => 1,
                                ],
                                [
                                    '_href' => 2,
                                ],
                                [
                                    '_href' => 3,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $policyCreate = $this->getParser();
        $policyCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test PolicyCreate parser throwing exception on missing function.
     */
    public function testParseExceptionOnMissingFunction()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'function\' attribute for PolicyCreate.');
        $inputArray = [
            'module' => 'content',
            'limitations' => [
                'limitation' => [
                    [
                        '_identifier' => 'Class',
                        'values' => [
                            'ref' => [
                                [
                                    '_href' => 1,
                                ],
                                [
                                    '_href' => 2,
                                ],
                                [
                                    '_href' => 3,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $policyCreate = $this->getParser();
        $policyCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test PolicyCreate parser throwing exception on missing identifier.
     */
    public function testParseExceptionOnMissingLimitationIdentifier()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'_identifier\' attribute for Limitation.');
        $inputArray = [
            'module' => 'content',
            'function' => 'delete',
            'limitations' => [
                'limitation' => [
                    [
                        'values' => [
                            'ref' => [
                                [
                                    '_href' => 1,
                                ],
                                [
                                    '_href' => 2,
                                ],
                                [
                                    '_href' => 3,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $policyCreate = $this->getParser();
        $policyCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test PolicyCreate parser throwing exception on missing values.
     */
    public function testParseExceptionOnMissingLimitationValues()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Invalid format for Limitation value in Limitation.');
        $inputArray = [
            'module' => 'content',
            'function' => 'delete',
            'limitations' => [
                'limitation' => [
                    [
                        '_identifier' => 'Class',
                    ],
                ],
            ],
        ];

        $policyCreate = $this->getParser();
        $policyCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the PolicyCreateStruct parser.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\PolicyCreate
     */
    protected function internalGetParser()
    {
        return new PolicyCreate(
            $this->getRoleServiceMock(),
            $this->getParserTools()
        );
    }

    /**
     * Get the role service mock object.
     *
     * @return \eZ\Publish\API\Repository\RoleService
     */
    protected function getRoleServiceMock()
    {
        $roleServiceMock = $this->createMock(RoleService::class);

        $roleServiceMock->expects($this->any())
            ->method('newPolicyCreateStruct')
            ->with(
                $this->equalTo('content'),
                $this->equalTo('delete')
            )
            ->willReturn(
                new PolicyCreateStruct(
                    [
                        'module' => 'content',
                        'function' => 'delete',
                    ]
                )
            );

        return $roleServiceMock;
    }
}
