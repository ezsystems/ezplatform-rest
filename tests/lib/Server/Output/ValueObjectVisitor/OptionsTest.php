<?php

/**
 * File containing a test class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Tests\Output\ValueObjectVisitorBaseTest;
use EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Server\Values;

class OptionsTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the NoContent visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $noContent = new Values\Options(array('GET', 'POST'));

        $this->getVisitorMock()->expects($this->once())
            ->method('setStatus')
            ->with($this->equalTo(200));

        $this->getVisitorMock()->expects($this->exactly(2))
            ->method('setHeader')
            ->will(
                $this->returnValueMap(
                    array('Allow', 'GET,POST'),
                    array('Content-Length', 0)
                )
            );

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $noContent
        );
    }

    /**
     * Get the NoContent visitor.
     *
     * @return \EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\NoContent
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\Options();
    }
}
