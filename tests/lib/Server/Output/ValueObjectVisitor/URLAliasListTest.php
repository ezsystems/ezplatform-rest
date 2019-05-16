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
use EzSystems\EzPlatformRest\Server\Values\URLAliasList;
use eZ\Publish\API\Repository\Values\Content;

class URLAliasListTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the URLAliasList visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $urlAliasList = new URLAliasList(array(), '/content/urlaliases');

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $urlAliasList
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        return $result;
    }

    /**
     * Test if result contains UrlAliasList element.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsUrlAliasListElement($result)
    {
        $this->assertXMLTag(
            array(
                'tag' => 'UrlAliasList',
            ),
            $result,
            'Invalid <UrlAliasList> element.',
            false
        );
    }

    /**
     * Test if result contains UrlAliasList element attributes.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsUrlAliasListAttributes($result)
    {
        $this->assertXMLTag(
            array(
                'tag' => 'UrlAliasList',
                'attributes' => array(
                    'media-type' => 'application/vnd.ez.api.UrlAliasList+xml',
                    'href' => '/content/urlaliases',
                ),
            ),
            $result,
            'Invalid <UrlAliasList> attributes.',
            false
        );
    }

    /**
     * Test if URLAliasList visitor visits the children.
     */
    public function testURLAliasListVisitsChildren()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $urlAliasList = new URLAliasList(
            array(
                new Content\URLAlias(),
                new Content\URLAlias(),
            ),
            '/content/urlaliases'
        );

        $this->getVisitorMock()->expects($this->exactly(2))
            ->method('visitValueObject')
            ->with($this->isInstanceOf(Content\URLAlias::class));

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $urlAliasList
        );
    }

    /**
     * Get the URLAliasList visitor.
     *
     * @return \EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\URLAliasList
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\URLAliasList();
    }
}
