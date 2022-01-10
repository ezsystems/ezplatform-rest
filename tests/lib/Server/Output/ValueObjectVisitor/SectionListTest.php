<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\Content;
use Ibexa\Rest\Server\Output\ValueObjectVisitor;
use Ibexa\Rest\Server\Values\SectionList;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;

class SectionListTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the SectionList visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $sectionList = new SectionList([], '/content/sections');

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $sectionList
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        return $result;
    }

    /**
     * Test if result contains SectionList element.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsSectionListElement($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'SectionList',
            ],
            $result,
            'Invalid <SectionList> element.',
            false
        );
    }

    /**
     * Test if result contains SectionList element attributes.
     *
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsSectionListAttributes($result)
    {
        $this->assertXMLTag(
            [
                'tag' => 'SectionList',
                'attributes' => [
                    'media-type' => 'application/vnd.ez.api.SectionList+xml',
                    'href' => '/content/sections',
                ],
            ],
            $result,
            'Invalid <SectionList> attributes.',
            false
        );
    }

    /**
     * Test if SectionList visitor visits the children.
     */
    public function testSectionListVisitsChildren()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $sectionList = new SectionList(
            [
                new Content\Section(),
                new Content\Section(),
            ],
            '/content/sections'
        );

        $this->getVisitorMock()->expects($this->exactly(2))
            ->method('visitValueObject')
            ->with($this->isInstanceOf(Content\Section::class));

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $sectionList
        );
    }

    /**
     * Get the SectionList visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\SectionList
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\SectionList();
    }
}

class_alias(SectionListTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\SectionListTest');
