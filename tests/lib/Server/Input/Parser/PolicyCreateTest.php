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

        $this->assertInternalType(
            'array',
            $parsedLimitations,
            'PolicyCreateStruct limitations not created correctly'
        );

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
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Missing 'module' attribute for PolicyCreate.
     */
    public function testParseExceptionOnMissingModule()
    {
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
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Missing 'function' attribute for PolicyCreate.
     */
    public function testParseExceptionOnMissingFunction()
    {
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
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Missing '_identifier' attribute for Limitation.
     */
    public function testParseExceptionOnMissingLimitationIdentifier()
    {
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
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Invalid format for Limitation value in Limitation.
     */
    public function testParseExceptionOnMissingLimitationValues()
    {
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
