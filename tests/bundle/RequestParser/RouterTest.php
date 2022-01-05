<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Bundle\Rest\RequestParser;

use Ibexa\Bundle\Rest\RequestParser\Router as RouterRequestParser;
use Ibexa\Contracts\Rest\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Cmf\Component\Routing\ChainRouter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

class RouterTest extends TestCase
{
    /**
     * @var \Symfony\Cmf\Component\Routing\ChainRouter
     */
    private $router;

    protected static $routePrefix = '/api/test/v1';

    public function testParse()
    {
        $uri = self::$routePrefix . '/';
        $request = Request::create($uri, 'GET');

        $expectedMatchResult = [
            '_route' => 'ezpublish_rest_testRoute',
            '_controller' => '',
        ];

        $this->getRouterMock()
            ->expects($this->once())
            ->method('matchRequest')
            ->willReturn($expectedMatchResult);

        self::assertEquals(
            $expectedMatchResult,
            $this->getRequestParser()->parse($uri)
        );
    }

    public function testParseNoMatch()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No route matched \'/api/test/v1/nomatch\'');

        $uri = self::$routePrefix . '/nomatch';

        $this->getRouterMock()
            ->expects($this->once())
            ->method('matchRequest')
            ->will($this->throwException(new ResourceNotFoundException()));

        $this->getRequestParser()->parse($uri);
    }

    public function testParseNoPrefix()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No route matched \'/no/prefix\'');

        $uri = '/no/prefix';

        $this->getRouterMock()
            ->expects($this->once())
            ->method('matchRequest')
            ->will($this->throwException(new ResourceNotFoundException()));

        $this->getRequestParser()->parse($uri);
    }

    public function testParseHref()
    {
        $href = '/api/test/v1/content/objects/1';

        $expectedMatchResult = [
            '_route' => 'ezpublish_rest_testParseHref',
            'contentId' => 1,
        ];

        $this->getRouterMock()
            ->expects($this->once())
            ->method('matchRequest')
            ->willReturn($expectedMatchResult);

        self::assertEquals(1, $this->getRequestParser()->parseHref($href, 'contentId'));
    }

    public function testParseHrefAttributeNotFound()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No attribute \'badAttribute\' in route matched from /api/test/v1/content/no-attribute');

        $href = '/api/test/v1/content/no-attribute';

        $matchResult = [
            '_route' => 'ezpublish_rest_testParseHrefAttributeNotFound',
        ];

        $this->getRouterMock()
            ->expects($this->once())
            ->method('matchRequest')
            ->willReturn($matchResult);

        self::assertEquals(1, $this->getRequestParser()->parseHref($href, 'badAttribute'));
    }

    public function testGenerate()
    {
        $routeName = 'ezpublish_rest_testGenerate';
        $arguments = ['arg1' => 1];

        $expectedResult = self::$routePrefix . '/generate/' . $arguments['arg1'];
        $this->getRouterMock()
            ->expects($this->once())
            ->method('generate')
            ->with($routeName, $arguments)
            ->willReturn($expectedResult);

        self::assertEquals(
            $expectedResult,
            $this->getRequestParser()->generate($routeName, $arguments)
        );
    }

    /**
     * @return \Ibexa\Bundle\Rest\RequestParser\Router
     */
    private function getRequestParser()
    {
        return new RouterRequestParser(
            $this->getRouterMock()
        );
    }

    /**
     * @return \Symfony\Cmf\Component\Routing\ChainRouter|\PHPUnit\Framework\MockObject\MockObject
     */
    private function getRouterMock()
    {
        if (!isset($this->router)) {
            $this->router = $this->createMock(ChainRouter::class);

            $this->router
                ->expects($this->any())
                ->method('getContext')
                ->willReturn(new RequestContext());
        }

        return $this->router;
    }
}

class_alias(RouterTest::class, 'EzSystems\EzPlatformRestBundle\Tests\RequestParser\RouterTest');
