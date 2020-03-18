<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use eZ\Publish\Core\Repository\ObjectStateService;
use EzSystems\EzPlatformRest\Server\Input\Parser\ObjectStateUpdate;
use eZ\Publish\API\Repository\Values\ObjectState\ObjectStateUpdateStruct;
use EzSystems\EzPlatformRest\Exceptions\Parser;

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
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\ObjectStateUpdate
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
     * @return \eZ\Publish\API\Repository\ObjectStateService
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
