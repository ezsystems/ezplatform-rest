<?php

/**
 * File containing a test class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser;

use EzSystems\EzPlatformRest\Input\Parser;

class ContentObjectStatesTest extends BaseTest
{
    /**
     * Tests the ContentObjectStates parser.
     */
    public function testParse()
    {
        $inputArray = array(
            'ObjectState' => array(
                array(
                    '_href' => '/content/objectstategroups/42/objectstates/21',
                ),
            ),
        );

        $objectState = $this->getParser();
        $result = $objectState->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInternalType(
            'array',
            $result,
            'ContentObjectStates not parsed correctly'
        );

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
     *
     * @expectedException \EzSystems\EzPlatformRest\Exceptions\Parser
     * @expectedExceptionMessage Missing '_href' attribute for ObjectState.
     */
    public function testParseExceptionOnMissingHref()
    {
        $inputArray = array(
            'ObjectState' => array(
                array(
                    '_href' => '/content/objectstategroups/42/objectstates/21',
                ),
                array(),
            ),
        );

        $objectState = $this->getParser();
        $objectState->parse($inputArray, $this->getParsingDispatcherMock());
    }

    public function getParseHrefExpectationsMap()
    {
        return array(
            array('/content/objectstategroups/42/objectstates/21', 'objectStateId', 21),
            array('/content/objectstategroups/42/objectstates/21', 'objectStateGroupId', 42),
        );
    }

    /**
     * Gets the ContentObjectStates parser.
     *
     * @return \EzSystems\EzPlatformRest\Input\Parser\ContentObjectStates;
     */
    protected function internalGetParser()
    {
        return new Parser\ContentObjectStates();
    }
}
