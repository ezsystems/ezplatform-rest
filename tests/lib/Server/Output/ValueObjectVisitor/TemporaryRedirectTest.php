<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Rest\Server\Output\ValueObjectVisitor;
use Ibexa\Rest\Server\Values;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;

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
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\TemporaryRedirect
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\TemporaryRedirect();
    }
}

class_alias(TemporaryRedirectTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\TemporaryRedirectTest');
