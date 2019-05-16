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
use EzSystems\EzPlatformRest\Server\Values;

class ResourceCreatedTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the ResourceCreated visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $resourceCreated = new Values\ResourceCreated(
            '/some/redirect/uri'
        );

        $this->getVisitorMock()->expects($this->once())
            ->method('setStatus')
            ->with($this->equalTo(201));
        $this->getVisitorMock()->expects($this->once())
            ->method('setHeader')
            ->with($this->equalTo('Location'), $this->equalTo('/some/redirect/uri'));

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $resourceCreated
        );

        $this->assertTrue($generator->isEmpty());
    }

    /**
     * Get the ResourceCreated visitor.
     *
     * @return \EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\ResourceCreated
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\ResourceCreated();
    }
}
