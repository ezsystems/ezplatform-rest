<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Bundle\Rest\RequestParser;

use Ibexa\Contracts\Rest\Exceptions\InvalidArgumentException;
use Ibexa\Rest\RequestParser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouterInterface;

/**
 * Router based request parser.
 */
class Router implements RequestParser
{
    /**
     * @var \Symfony\Cmf\Component\Routing\ChainRouter
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @throws \Symfony\Component\Routing\Exception\ResourceNotFoundException If no match was found
     */
    public function parse($url)
    {
        // we create a request with a new context in order to match $url to a route and get its properties
        $request = Request::create($url, 'GET');
        $originalContext = $this->router->getContext();
        $context = clone $originalContext;
        $context->fromRequest($request);
        $this->router->setContext($context);

        try {
            $matchResult = $this->router->matchRequest($request);
        } catch (ResourceNotFoundException $e) {
            // Note: this probably won't occur in real life because of the legacy matcher
            $this->router->setContext($originalContext);
            throw new InvalidArgumentException("No route matched '$url'");
        }

        if (!$this->matchesRestRequest($matchResult)) {
            $this->router->setContext($originalContext);
            throw new InvalidArgumentException("No route matched '$url'");
        }

        $this->router->setContext($originalContext);

        return $matchResult;
    }

    public function generate($type, array $values = [])
    {
        return $this->router->generate($type, $values);
    }

    /**
     * @throws \Ibexa\Core\Base\Exceptions\InvalidArgumentException If $attribute wasn't found in the match
     */
    public function parseHref($href, $attribute)
    {
        $parsingResult = $this->parse($href);

        if (!isset($parsingResult[$attribute])) {
            throw new InvalidArgumentException("No attribute '$attribute' in route matched from $href");
        }

        return $parsingResult[$attribute];
    }

    /**
     * Checks if a router match response matches a REST resource.
     *
     * @param array $match Match array returned by Router::match() / Router::matchRequest()
     *
     * @throws \Ibexa\Contracts\Rest\Exceptions\InvalidArgumentException if the \$match isn't valid
     *
     * @return bool
     */
    private function matchesRestRequest(array $match)
    {
        if (!isset($match['_route'])) {
            throw new InvalidArgumentException('Invalid $match parameter, no _route key');
        }

        return strpos($match['_route'], 'ezpublish_rest_') === 0;
    }
}

class_alias(Router::class, 'EzSystems\EzPlatformRestBundle\RequestParser\Router');
