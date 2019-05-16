<?php

/**
 * File containing a test class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Tests\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Tests\Output\ValueObjectVisitorBaseTest;
use EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Server\Values\Trash;
use EzSystems\EzPlatformRest\Server\Values\RestTrashItem;
use eZ\Publish\Core\Repository\Values\Content;

class TrashTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the Trash visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $trash = new Trash(array(), '/content/trash');

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $trash
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        return $result;
    }

    /**
     * Test if result contains Trash element.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsTrashElement($result)
    {
        $this->assertXMLTag(
            array(
                'tag' => 'Trash',
            ),
            $result,
            'Invalid <Trash> element.',
            false
        );
    }

    /**
     * Test if result contains Trash element attributes.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsTrashAttributes($result)
    {
        $this->assertXMLTag(
            array(
                'tag' => 'Trash',
                'attributes' => array(
                    'media-type' => 'application/vnd.ez.api.Trash+xml',
                    'href' => '/content/trash',
                ),
            ),
            $result,
            'Invalid <Trash> attributes.',
            false
        );
    }

    /**
     * Test if Trash visitor visits the children.
     */
    public function testTrashVisitsChildren()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $trashList = new Trash(
            array(
                new RestTrashItem(
                    new Content\TrashItem(),
                    // Dummy value for ChildCount
                    0
                ),
                new RestTrashItem(
                    new Content\TrashItem(),
                    // Dummy value for ChildCount
                    0
                ),
            ),
            '/content/trash'
        );

        $this->getVisitorMock()->expects($this->exactly(2))
            ->method('visitValueObject')
            ->with($this->isInstanceOf(RestTrashItem::class));

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $trashList
        );
    }

    /**
     * Get the Trash visitor.
     *
     * @return \EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\Trash
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\Trash();
    }
}
