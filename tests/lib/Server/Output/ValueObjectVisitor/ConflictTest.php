<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Tests\Output\ValueObjectVisitorBaseTest;
use EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Server\Values;

class ConflictTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the Conflict visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $noContent = new Values\Conflict();

        $this->getVisitorMock()->expects($this->once())
            ->method('setStatus')
            ->with($this->equalTo(409));

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $noContent
        );

        $this->assertTrue($generator->isEmpty());
    }

    /**
     * Get the Conflict visitor.
     *
     * @return \EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\Conflict
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\Conflict();
    }
}
