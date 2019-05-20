<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Tests\Output\ValueObjectVisitorBaseTest;
use EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Server\Values;

class TemporaryRedirectTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the TemporaryRedirect visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $redirect = new Values\TemporaryRedirect('/some/redirect/uri');

        $this->getVisitorMock()->expects($this->once())
            ->method('setStatus')
            ->with($this->equalTo(307));
        $this->getVisitorMock()->expects($this->once())
            ->method('setHeader')
            ->with($this->equalTo('Location'), $this->equalTo('/some/redirect/uri'));

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $redirect
        );

        $this->assertTrue($generator->isEmpty());
    }

    /**
     * Get the TemporaryRedirect visitor.
     *
     * @return \EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\TemporaryRedirect
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\TemporaryRedirect();
    }
}
