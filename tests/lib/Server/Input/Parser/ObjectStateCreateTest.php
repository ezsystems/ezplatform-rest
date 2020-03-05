<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use eZ\Publish\Core\Repository\ObjectStateService;
use EzSystems\EzPlatformRest\Server\Input\Parser\ObjectStateCreate;
use eZ\Publish\API\Repository\Values\ObjectState\ObjectStateCreateStruct;
use EzSystems\EzPlatformRest\Exceptions\Parser;

class ObjectStateCreateTest extends BaseTest
{
    /**
     * Tests the ObjectStateCreate parser.
     */
    public function testParse()
    {
        $inputArray = [
            'identifier' => 'test-state',
            'priority' => '0',
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

        $objectStateCreate = $this->getParser();
        $result = $objectStateCreate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            ObjectStateCreateStruct::class,
            $result,
            'ObjectStateCreateStruct not created correctly.'
        );

        $this->assertEquals(
            'test-state',
            $result->identifier,
            'ObjectStateCreateStruct identifier property not created correctly.'
        );

        $this->assertEquals(
            0,
            $result->priority,
            'ObjectStateCreateStruct priority property not created correctly.'
        );

        $this->assertEquals(
            'eng-GB',
            $result->defaultLanguageCode,
            'ObjectStateCreateStruct defaultLanguageCode property not created correctly.'
        );

        $this->assertEquals(
            ['eng-GB' => 'Test state'],
            $result->names,
            'ObjectStateCreateStruct names property not created correctly.'
        );

        $this->assertEquals(
            ['eng-GB' => 'Test description'],
            $result->descriptions,
            'ObjectStateCreateStruct descriptions property not created correctly.'
        );
    }

    /**
     * Test ObjectStateCreate parser throwing exception on missing identifier.
     */
    public function testParseExceptionOnMissingIdentifier()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'identifier\' attribute for ObjectStateCreate.');
        $inputArray = [
            'priority' => '0',
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

        $objectStateCreate = $this->getParser();
        $objectStateCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ObjectStateCreate parser throwing exception on missing priority.
     */
    public function testParseExceptionOnMissingPriority()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'priority\' attribute for ObjectStateCreate.');
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

        $objectStateCreate = $this->getParser();
        $objectStateCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ObjectStateCreate parser throwing exception on missing defaultLanguageCode.
     */
    public function testParseExceptionOnMissingDefaultLanguageCode()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'defaultLanguageCode\' attribute for ObjectStateCreate.');
        $inputArray = [
            'identifier' => 'test-state',
            'priority' => '0',
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

        $objectStateCreate = $this->getParser();
        $objectStateCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ObjectStateCreate parser throwing exception on missing names.
     */
    public function testParseExceptionOnMissingNames()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing or invalid \'names\' element for ObjectStateCreate.');
        $inputArray = [
            'identifier' => 'test-state',
            'priority' => '0',
            'defaultLanguageCode' => 'eng-GB',
            'descriptions' => [
                'value' => [
                    [
                        '_languageCode' => 'eng-GB',
                        '#text' => 'Test description',
                    ],
                ],
            ],
        ];

        $objectStateCreate = $this->getParser();
        $objectStateCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ObjectStateCreate parser throwing exception on invalid names structure.
     */
    public function testParseExceptionOnInvalidNames()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing or invalid \'names\' element for ObjectStateCreate.');
        $inputArray = [
            'identifier' => 'test-state',
            'priority' => '0',
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

        $objectStateCreate = $this->getParser();
        $objectStateCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the ObjectStateCreate parser.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\ObjectStateCreate
     */
    protected function internalGetParser()
    {
        return new ObjectStateCreate(
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
            ->method('newObjectStateCreateStruct')
            ->with($this->equalTo('test-state'))
            ->willReturn(
                new ObjectStateCreateStruct(['identifier' => 'test-state'])
            );

        return $objectStateServiceMock;
    }
}
