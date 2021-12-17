<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\Values\User\Limitation;
use Ibexa\Contracts\Rest\Exceptions\Parser;
use Ibexa\Core\Repository\RoleService;
use Ibexa\Core\Repository\Values\User\PolicyCreateStruct;
use Ibexa\Rest\Server\Input\Parser\PolicyCreate;

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
     * @return \Ibexa\Rest\Server\Input\Parser\PolicyCreate
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
     * @return \Ibexa\Contracts\Core\Repository\RoleService
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

class_alias(PolicyCreateTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\PolicyCreateTest');
