<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Input\Parser;

use Ibexa\Rest\Input\Parser;

class ContentObjectStatesTest extends BaseTest
{
    /**
     * Tests the ContentObjectStates parser.
     */
    public function testParse()
    {
        $inputArray = [
            'ObjectState' => [
                [
                    '_href' => '/content/objectstategroups/42/objectstates/21',
                ],
            ],
        ];

        $objectState = $this->getParser();
        $result = $objectState->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertIsArray($result, 'ContentObjectStates not parsed correctly');

        $this->assertNotEmpty(
            $result,
            'ContentObjectStates has no ObjectState elements'
        );

        $this->assertInstanceOf(
            '\\EzSystems\\EzPlatformRest\\Values\\RestObjectState',
            $result[0],
            'ObjectState not created correctly.'
        );

        $this->assertInstanceOf(
            '\\eZ\\Publish\\API\\Repository\\Values\\ObjectState\\ObjectState',
            $result[0]->objectState,
            'Inner ObjectState not created correctly.'
        );

        $this->assertEquals(
            21,
            $result[0]->objectState->id,
            'Inner ObjectState id property not created correctly.'
        );

        $this->assertEquals(
            42,
            $result[0]->groupId,
            'groupId property not created correctly.'
        );
    }

    /**
     * Test ContentObjectStates parser throwing exception on missing href.
     */
    public function testParseExceptionOnMissingHref()
    {
        $this->expectException('EzSystems\EzPlatformRest\Exceptions\Parser');
        $this->expectExceptionMessage('Missing \'_href\' attribute for ObjectState.');
        $inputArray = [
            'ObjectState' => [
                [
                    '_href' => '/content/objectstategroups/42/objectstates/21',
                ],
                [],
            ],
        ];

        $objectState = $this->getParser();
        $objectState->parse($inputArray, $this->getParsingDispatcherMock());
    }

    public function getParseHrefExpectationsMap()
    {
        return [
            ['/content/objectstategroups/42/objectstates/21', 'objectStateId', 21],
            ['/content/objectstategroups/42/objectstates/21', 'objectStateGroupId', 42],
        ];
    }

    /**
     * Gets the ContentObjectStates parser.
     *
     * @return \Ibexa\Rest\Input\Parser\ContentObjectStates ;
     */
    protected function internalGetParser()
    {
        return new Parser\ContentObjectStates();
    }
}

class_alias(ContentObjectStatesTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\ContentObjectStatesTest');
