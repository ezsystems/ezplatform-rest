<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Rest\Server\Output\ValueObjectVisitor;
use Ibexa\Rest\Server\Values\CountryList;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;

class CountryListTest extends ValueObjectVisitorBaseTest
{
    /**
     * Test the CountryListList visitor.
     *
     * @return string
     */
    public function testVisit()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $countryList = new CountryList(
            [
                'VA' => [
                    'Name' => 'Holy See (Vatican City State)',
                    'Alpha2' => 'VA',
                    'Alpha3' => 'VAT',
                    'IDC' => '3906',
                ],
                'HM' => [
                    'Name' => 'Heard Island and McDonald Islands',
                    'Alpha2' => 'HM',
                    'Alpha3' => 'HMD',
                    'IDC' => '672',
                ],
            ]
        );

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $countryList
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        $dom = new \DOMDocument();

        $dom->loadXml($result);

        return $dom;
    }

    /**
     * @param \DOMDocument $dom
     *
     * @depends testVisit
     */
    public function testCountryListMediaType(\DOMDocument $dom)
    {
        $this->assertXPath($dom, '/CountryList/Country[1][@media-type="application/vnd.ez.api.Country+xml"]');
        $this->assertXPath($dom, '/CountryList/Country[2][@media-type="application/vnd.ez.api.Country+xml"]');
    }

    /**
     * @param \DOMDocument $dom
     *
     * @depends testVisit
     */
    public function testCountryListId(\DOMDocument $dom)
    {
        $this->assertXPath($dom, '/CountryList/Country[1][@id="VA"]');
        $this->assertXPath($dom, '/CountryList/Country[2][@id="HM"]');
    }

    /**
     * @param \DOMDocument $dom
     *
     * @depends testVisit
     */
    public function testCountryListName(\DOMDocument $dom)
    {
        $this->assertXPath($dom, '/CountryList/Country[1]/name[text()="Holy See (Vatican City State)"]');
        $this->assertXPath($dom, '/CountryList/Country[2]/name[text()="Heard Island and McDonald Islands"]');
    }

    /**
     * @param \DOMDocument $dom
     *
     * @depends testVisit
     */
    public function testCountryListAlpha2(\DOMDocument $dom)
    {
        $this->assertXPath($dom, '/CountryList/Country[1]/Alpha2[text()="VA"]');
        $this->assertXPath($dom, '/CountryList/Country[2]/Alpha2[text()="HM"]');
    }

    /**
     * @param \DOMDocument $dom
     *
     * @depends testVisit
     */
    public function testCountryListAlpha3(\DOMDocument $dom)
    {
        $this->assertXPath($dom, '/CountryList/Country[1]/Alpha3[text()="VAT"]');
        $this->assertXPath($dom, '/CountryList/Country[2]/Alpha3[text()="HMD"]');
    }

    /**
     * @param \DOMDocument $dom
     *
     * @depends testVisit
     */
    public function testCountryListIDC(\DOMDocument $dom)
    {
        $this->assertXPath($dom, '/CountryList/Country[1]/IDC[text()="3906"]');
        $this->assertXPath($dom, '/CountryList/Country[2]/IDC[text()="672"]');
    }

    /**
     * Get the CountryList visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\CountryList
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\CountryList();
    }
}

class_alias(CountryListTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\CountryListTest');
