<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use eZ\Publish\Core\Repository\ObjectStateService;
use EzSystems\EzPlatformRest\Server\Input\Parser\ObjectStateGroupCreate;
use eZ\Publish\API\Repository\Values\ObjectState\ObjectStateGroupCreateStruct;
use EzSystems\EzPlatformRest\Exceptions\Parser;

class ObjectStateGroupCreateTest extends BaseTest
{
    /**
     * Tests the ObjectStateGroupCreate parser.
     */
    public function testParse()
    {
        $inputArray = [
            'identifier' => 'test-group',
            'defaultLanguageCode' => 'eng-GB',
            'names' => [
                'value' => [
                    [
                        '_languageCode' => 'eng-GB',
                        '#text' => 'Test group',
                    ],
                ],
            ],
            'descriptions' => [
                'value' => [
                    [
                        '_languageCode' => 'eng-GB',
                        '#text' => 'Test group description',
                    ],
                ],
            ],
        ];

        $objectStateGroupCreate = $this->getParser();
        $result = $objectStateGroupCreate->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            ObjectStateGroupCreateStruct::class,
            $result,
            'ObjectStateGroupCreateStruct not created correctly.'
        );

        $this->assertEquals(
            'test-group',
            $result->identifier,
            'ObjectStateGroupCreateStruct identifier property not created correctly.'
        );

        $this->assertEquals(
            'eng-GB',
            $result->defaultLanguageCode,
            'ObjectStateGroupCreateStruct defaultLanguageCode property not created correctly.'
        );

        $this->assertEquals(
            ['eng-GB' => 'Test group'],
            $result->names,
            'ObjectStateGroupCreateStruct names property not created correctly.'
        );

        $this->assertEquals(
            ['eng-GB' => 'Test group description'],
            $result->descriptions,
            'ObjectStateGroupCreateStruct descriptions property not created correctly.'
        );
    }

    /**
     * Test ObjectStateGroupCreate parser throwing exception on missing identifier.
     */
    public function testParseExceptionOnMissingIdentifier()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'identifier\' attribute for ObjectStateGroupCreate.');
        $inputArray = [
            'defaultLanguageCode' => 'eng-GB',
            'names' => [
                'value' => [
                    [
                        '_languageCode' => 'eng-GB',
                        '#text' => 'Test group',
                    ],
                ],
            ],
            'descriptions' => [
                'value' => [
                    [
                        '_languageCode' => 'eng-GB',
                        '#text' => 'Test group description',
                    ],
                ],
            ],
        ];

        $objectStateGroupCreate = $this->getParser();
        $objectStateGroupCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ObjectStateGroupCreate parser throwing exception on missing defaultLanguageCode.
     */
    public function testParseExceptionOnMissingDefaultLanguageCode()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'defaultLanguageCode\' attribute for ObjectStateGroupCreate.');
        $inputArray = [
            'identifier' => 'test-group',
            'names' => [
                'value' => [
                    [
                        '_languageCode' => 'eng-GB',
                        '#text' => 'Test group',
                    ],
                ],
            ],
            'descriptions' => [
                'value' => [
                    [
                        '_languageCode' => 'eng-GB',
                        '#text' => 'Test group description',
                    ],
                ],
            ],
        ];

        $objectStateGroupCreate = $this->getParser();
        $objectStateGroupCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ObjectStateGroupCreate parser throwing exception on missing names.
     */
    public function testParseExceptionOnMissingNames()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing or invalid \'names\' element for ObjectStateGroupCreate.');
        $inputArray = [
            'identifier' => 'test-group',
            'defaultLanguageCode' => 'eng-GB',
            'descriptions' => [
                'value' => [
                    [
                        '_languageCode' => 'eng-GB',
                        '#text' => 'Test group description',
                    ],
                ],
            ],
        ];

        $objectStateGroupCreate = $this->getParser();
        $objectStateGroupCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test ObjectStateGroupCreate parser throwing exception on invalid names structure.
     */
    public function testParseExceptionOnInvalidNames()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing or invalid \'names\' element for ObjectStateGroupCreate.');
        $inputArray = [
            'identifier' => 'test-group',
            'defaultLanguageCode' => 'eng-GB',
            'names' => [],
            'descriptions' => [
                'value' => [
                    [
                        '_languageCode' => 'eng-GB',
                        '#text' => 'Test group description',
                    ],
                ],
            ],
        ];

        $objectStateGroupCreate = $this->getParser();
        $objectStateGroupCreate->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the ObjectStateGroupCreate parser.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\ObjectStateGroupCreate
     */
    protected function internalGetParser()
    {
        return new ObjectStateGroupCreate(
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
            ->method('newObjectStateGroupCreateStruct')
            ->with($this->equalTo('test-group'))
            ->willReturn(
                new ObjectStateGroupCreateStruct(['identifier' => 'test-group'])
            );

        return $objectStateServiceMock;
    }
}
