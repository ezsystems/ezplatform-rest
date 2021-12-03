<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\Values\ObjectState\ObjectStateUpdateStruct;
use Ibexa\Contracts\Rest\Exceptions\Parser;
use Ibexa\Core\Repository\ObjectStateService;
use Ibexa\Rest\Server\Input\Parser\ObjectStateUpdate;

class ObjectStateUpdateTest extends BaseTest
{
    /**
     * Tests the ObjectStateUpdate parser.
     */
    public function testParse()
    {
        $inputArray = [
            'identifier' => 'test-state',
            'defaultLanguageCode' => 'eng-GB',
            'names' => [
                'value' => [
                    [
                        '_languageCode' => 'eng-GB',
                        '#text' => 'Test state',
                    ],
                ],
            ],
            'descriptions' => [
                'value' => [
                    [
                        '_languageCode' => 'eng-GB',
                        '#text' => 'Test description',
                    ],
                ],
            ],
        ];

        $objectStateUpdate = $this->getParser();
        $result = $objectStateUpdate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            ObjectStateUpdateStruct::class,
            $result,
            'ObjectStateUpdateStruct not created correctly.'
        );

        $this->assertEquals(
            'test-state',
            $result->identifier,
            'ObjectStateUpdateStruct identifier property not created correctly.'
        );

        $this->assertEquals(
            'eng-GB',
            $result->defaultLanguageCode,
            'ObjectStateUpdateStruct defaultLanguageCode property not created correctly.'
        );

        $this->assertEquals(
            ['eng-GB' => 'Test state'],
            $result->names,
            'ObjectStateUpdateStruct names property not created correctly.'
        );

        $this->assertEquals(
            ['eng-GB' => 'Test description'],
            $result->descriptions,
            'ObjectStateUpdateStruct descriptions property not created correctly.'
        );
    }

    /**
     * Test ObjectStateUpdate parser throwing exception on invalid names structure.
     */
    public function testParseExceptionOnInvalidNames()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing or invalid \'names\' element for ObjectStateUpdate.');
        $inputArray = [
            'identifier' => 'test-state',
            'defaultLanguageCode' => 'eng-GB',
            'names' => [],
            'descriptions' => [
                'value' => [
                    [
                        '_languageCode' => 'eng-GB',
                        '#text' => 'Test description',
                    ],
                ],
            ],
        ];

        $objectStateUpdate = $this->getParser();
        $objectStateUpdate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the ObjectStateUpdate parser.
     *
     * @return \Ibexa\Rest\Server\Input\Parser\ObjectStateUpdate
     */
    protected function internalGetParser()
    {
        return new ObjectStateUpdate(
            $this->getObjectStateServiceMock(),
            $this->getParserTools()
        );
    }

    /**
     * Get the object state service mock object.
     *
     * @return \Ibexa\Contracts\Core\Repository\ObjectStateService
     */
    protected function getObjectStateServiceMock()
    {
        $objectStateServiceMock = $this->createMock(ObjectStateService::class);

        $objectStateServiceMock->expects($this->any())
            ->method('newObjectStateUpdateStruct')
            ->willReturn(
                new ObjectStateUpdateStruct()
            );

        return $objectStateServiceMock;
    }
}

class_alias(ObjectStateUpdateTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\ObjectStateUpdateTest');
