<?php

/**
 * File containing a test class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Tests\Output\ValueObjectVisitorBaseTest;
use EzSystems\EzPlatformRest\Values\RestObjectState;
use EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Server\Values\ObjectStateList;
use eZ\Publish\Core\Repository\Values\ObjectState\ObjectState;

class ObjectStateListTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the ObjectStateList visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        // @todo coverage add actual object states + visitor mock for RestObjectState
        $stateList = new ObjectStateList(array(), 42);

        $this->addRouteExpectation(
            'ezpublish_rest_loadObjectStates',
            array('objectStateGroupId' => $stateList->groupId),
            "/content/objectstategroups/{$stateList->groupId}/objectstates"
        );

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $stateList
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        return $result;
    }

    /**
     * Test if result contains ObjectStateList element.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsObjectStateListElement($result)
    {
        $this->assertXMLTag(
            array(
                'tag' => 'ObjectStateList',
            ),
            $result,
            'Invalid <ObjectStateList> element.',
            false
        );
    }

    /**
     * Test if result contains ObjectStateList element attributes.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsObjectStateListAttributes($result)
    {
        $this->assertXMLTag(
            array(
                'tag' => 'ObjectStateList',
                'attributes' => array(
                    'media-type' => 'application/vnd.ez.api.ObjectStateList+xml',
                    'href' => '/content/objectstategroups/42/objectstates',
                ),
            ),
            $result,
            'Invalid <ObjectStateList> attributes.',
            false
        );
    }

    /**
     * Test if ObjectStateList visitor visits the children.
     */
    public function testObjectStateListVisitsChildren()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $objectStateList = new ObjectStateList(
            array(
                new ObjectState(),
                new ObjectState(),
            ),
            42
        );

        $this->getVisitorMock()->expects($this->exactly(2))
            ->method('visitValueObject')
            ->with($this->isInstanceOf(RestObjectState::class));

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $objectStateList
        );
    }

    /**
     * Get the ObjectStateList visitor.
     *
     * @return \EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\ObjectStateList
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\ObjectStateList();
    }
}
