<?php

/**
 * File containing the DeletedUserSessionTest class.
 *
 * @copyright Copyright (C) 1999-2014 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Tests\Output\ValueObjectVisitorBaseTest;
use EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Server\Values;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class DeletedUserSessionTest extends ValueObjectVisitorBaseTest
{
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $generatedResponse = new Response();
        $responseHeaders = array(
            'foo' => 'bar',
            'some' => 'thing',
        );
        $cookie = new Cookie('cookie_name', 'cookie_value');
        $generatedResponse->headers->add($responseHeaders);
        $generatedResponse->headers->setCookie($cookie);
        $deletedSessionValue = new Values\DeletedUserSession($generatedResponse);

        $outputVisitor = $this->getVisitorMock();
        $outputVisitor->expects($this->once())
            ->method('setStatus')
            ->with($this->equalTo(204));

        $visitor->visit(
            $outputVisitor,
            $generator,
            $deletedSessionValue
        );

        $this->assertTrue($generator->isEmpty());
        $this->assertSame('bar', $this->getResponseMock()->headers->get('foo'));
        $this->assertSame('thing', $this->getResponseMock()->headers->get('some'));
        $this->assertSame(array($cookie), $this->getResponseMock()->headers->getCookies());
    }

    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\DeletedUserSession();
    }
}
