<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\UrlHandler;

use Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException;
use Ibexa\Rest\RequestParser\Pattern;
use PHPUnit\Framework\TestCase;

/**
 * Test for Pattern based url handler.
 */
class PatternTest extends TestCase
{
    /**
     * Tests parsing unknown URL type.
     */
    public function testParseUnknownUrlType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("URL '/foo' did not match any route.");

        $urlHandler = new Pattern();
        $urlHandler->parse('/foo');
    }

    /**
     * Tests parsing invalid pattern.
     */
    public function testParseInvalidPattern()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid pattern part: '{broken'.");

        $urlHandler = new Pattern(
            [
                'invalid' => '/foo/{broken',
            ]
        );
        $urlHandler->parse('/foo');
    }

    /**
     * Tests parsing when pattern does not match.
     */
    public function testPatternDoesNotMatch()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("URL '/bar' did not match any route.");

        $urlHandler = new Pattern(
            [
                'pattern' => '/foo/{foo}',
            ]
        );
        $urlHandler->parse('/bar');
    }

    /**
     * Test parsing when pattern does not match the end of the URL.
     */
    public function testPatternDoesNotMatchTrailing()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("URL '/foo/23/bar' did not match any route.");

        $urlHandler = new Pattern(
            [
                'pattern' => '/foo/{foo}',
            ]
        );
        $urlHandler->parse('/foo/23/bar');
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public static function getParseValues()
    {
        return [
            [
                'section',
                '/content/section/42',
                [
                    'section' => '42',
                ],
            ],
            [
                'objectversion',
                '/content/object/42/23',
                [
                    'object' => '42',
                    'version' => '23',
                ],
            ],
            [
                'location',
                '/content/locations/23/42/100',
                [
                    'location' => '23/42/100',
                ],
            ],
            [
                'locationChildren',
                '/content/locations/23/42/100/children',
                [
                    'location' => '23/42/100',
                ],
            ],
        ];
    }

    /**
     * Test parsing URL.
     *
     * @dataProvider getParseValues
     */
    public function testParseUrl($type, $url, $values)
    {
        $urlHandler = $this->getWorkingUrlHandler();

        $this->assertSame(
            $values,
            $urlHandler->parse($url)
        );
    }

    /**
     * Test generating unknown URL type.
     */
    public function testGenerateUnknownUrlType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("No URL for type 'unknown' available.");

        $urlHandler = new Pattern();
        $urlHandler->generate('unknown', []);
    }

    /**
     * Test generating URL with missing value.
     */
    public function testGenerateMissingValue()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("No value provided for 'unknown'.");

        $urlHandler = new Pattern(
            [
                'pattern' => '/foo/{unknown}',
            ]
        );
        $urlHandler->generate('pattern', []);
    }

    /**
     * Test generating URL with extra value.
     */
    public function testGenerateSuperfluousValue()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unused values in values array: 'bar'.");

        $urlHandler = new Pattern(
            [
                'pattern' => '/foo/{foo}',
            ]
        );
        $urlHandler->generate(
            'pattern',
            [
                'foo' => 23,
                'bar' => 42,
            ]
        );
    }

    /**
     * Data provider.
     *
     * @dataProvider getParseValues
     */
    public function testGenerateUrl($type, $url, $values)
    {
        $urlHandler = $this->getWorkingUrlHandler();

        $this->assertSame(
            $url,
            $urlHandler->generate($type, $values)
        );
    }

    /**
     * Returns the URL handler.
     *
     * @return \Ibexa\Rest\RequestParser\Pattern
     */
    protected function getWorkingUrlHandler()
    {
        return new Pattern(
            [
                'section' => '/content/section/{section}',
                'objectversion' => '/content/object/{object}/{version}',
                'locationChildren' => '/content/locations/{&location}/children',
                'location' => '/content/locations/{&location}',
            ]
        );
    }
}

class_alias(PatternTest::class, 'EzSystems\EzPlatformRest\Tests\UrlHandler\PatternTest');
