<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Bundle\Rest\CorsOptions;

use Exception;
use Ibexa\Bundle\Rest\CorsOptions\RestProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;

class RestProviderTest extends TestCase
{
    /**
     * Return value expectation for RequestMatcher::matchRequest
     * Set to false to expect Router::match() never to be called, or to an exception to have it throw one.
     */
    protected $matchRequestResult = [];

    public function testGetOptions()
    {
        $this->matchRequestResult = ['allowedMethods' => 'GET,POST,DELETE'];

        self::assertEquals(
            [
                'allow_methods' => ['GET', 'POST', 'DELETE'],
            ],
            $this->getProvider()->getOptions($this->createRequest())
        );
    }

    public function testGetOptionsResourceNotFound()
    {
        $this->matchRequestResult = new ResourceNotFoundException();
        self::assertEquals(
            [
                'allow_methods' => [],
            ],
            $this->getProvider()->getOptions($this->createRequest())
        );
    }

    public function testGetOptionsMethodNotAllowed()
    {
        $this->matchRequestResult = new MethodNotAllowedException(['OPTIONS']);
        self::assertEquals(
            [
                'allow_methods' => [],
            ],
            $this->getProvider()->getOptions($this->createRequest())
        );
    }

    public function testGetOptionsException()
    {
        $this->expectException(Exception::class);

        $this->matchRequestResult = new Exception();
        $this->getProvider()->getOptions($this->createRequest());
    }

    public function testGetOptionsNoMethods()
    {
        $this->matchRequestResult = [];
        self::assertEquals(
            [
                'allow_methods' => [],
            ],
            $this->getProvider()->getOptions($this->createRequest())
        );
    }

    public function testGetOptionsNotRestRequest()
    {
        $this->matchRequestResult = false;
        self::assertEquals(
            [],
            $this->getProvider()->getOptions($this->createRequest(false))
        );
    }

    /**
     * @param bool $isRestRequest wether or not to set the is_rest_request attribute
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function createRequest($isRestRequest = true)
    {
        $request = new Request();
        if ($isRestRequest) {
            $request->attributes->set('is_rest_request', true);
        }

        return $request;
    }

    protected function getProvider()
    {
        return new RestProvider(
            $this->getRequestMatcherMock()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Routing\Matcher\RequestMatcherInterface
     */
    protected function getRequestMatcherMock()
    {
        $mock = $this->createMock(RequestMatcherInterface::class);

        if ($this->matchRequestResult instanceof Exception) {
            $mock->expects($this->any())
                ->method('matchRequest')
                ->will($this->throwException($this->matchRequestResult));
        } elseif ($this->matchRequestResult === false) {
            $mock->expects($this->never())
                ->method('matchRequest');
        } else {
            $mock->expects($this->any())
                ->method('matchRequest')
                ->willReturn($this->matchRequestResult);
        }

        return $mock;
    }
}

class_alias(RestProviderTest::class, 'EzSystems\EzPlatformRestBundle\Tests\CorsOptions\RestProviderTest');
