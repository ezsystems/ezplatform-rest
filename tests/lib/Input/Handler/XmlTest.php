<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Input\Handler;

use Ibexa\Contracts\Rest\Exceptions\Parser;
use Ibexa\Rest\Input\Handler\Xml;
use PHPUnit\Framework\TestCase;

/**
 * Xml input handler test class.
 */
class XmlTest extends TestCase
{
    public function testConvertInvalidXml()
    {
        $this->expectException(Parser::class);

        $handler = new Xml();

        $this->assertSame(
            [
                'text' => 'Hello world!',
            ],
            $handler->convert('{"text":"Hello world!"}')
        );
    }

    public static function getXmlFixtures()
    {
        $fixtures = [];
        foreach (glob(__DIR__ . '/_fixtures/*.xml') as $xmlFile) {
            $fixtures[] = [
                file_get_contents($xmlFile),
                is_file($xmlFile . '.php') ? include $xmlFile . '.php' : null,
            ];
        }

        return $fixtures;
    }

    /**
     * @dataProvider getXmlFixtures
     */
    public function testConvertXml($xml, $expectation)
    {
        $handler = new Xml();

        $this->assertSame(
            $expectation,
            $handler->convert($xml)
        );
    }
}

class_alias(XmlTest::class, 'EzSystems\EzPlatformRest\Tests\Input\Handler\XmlTest');
