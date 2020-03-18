<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use eZ\Publish\API\Repository\Values\User\Limitation;
use eZ\Publish\Core\Repository\RoleService;
use EzSystems\EzPlatformRest\Server\Input\Parser\PolicyUpdate;
use eZ\Publish\Core\Repository\Values\User\PolicyUpdateStruct;
use EzSystems\EzPlatformRest\Exceptions\Parser;

class PolicyUpdateTest extends BaseTest
{
    /**
     * Tests the PolicyUpdate parser.
     */
    public function testParse()
    {
        $inputArray = [
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

        $policyUpdate = $this->getParser();
        $result = $policyUpdate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            PolicyUpdateStruct::class,
            $result,
            'PolicyUpdateStruct not created correctly.'
        );

        $parsedLimitations = $result->getLimitations();

        $this->assertIsArray($parsedLimitations, 'PolicyUpdateStruct limitations not created correctly');

        $this->assertCount(
            1,
            $parsedLimitations,
            'PolicyUpdateStruct limitations not created correctly'
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
     * Test PolicyUpdate parser throwing exception on missing identifier.
     */
    public function testParseExceptionOnMissingLimitationIdentifier()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'_identifier\' attribute for Limitation.');
        $inputArray = [
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

        $policyUpdate = $this->getParser();
        $policyUpdate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test PolicyUpdate parser throwing exception on missing values.
     */
    public function testParseExceptionOnMissingLimitationValues()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Invalid format for Limitation value in Limitation.');
        $inputArray = [
            'limitations' => [
                'limitation' => [
                    [
                        '_identifier' => 'Class',
                    ],
                ],
            ],
        ];

        $policyUpdate = $this->getParser();
        $policyUpdate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the PolicyUpdateStruct parser.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\PolicyUpdate
     */
    protected function internalGetParser()
    {
        return new PolicyUpdate(
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
            ->method('newPolicyUpdateStruct')
            ->willReturn(
                new PolicyUpdateStruct()
            );

        return $roleServiceMock;
    }
}
