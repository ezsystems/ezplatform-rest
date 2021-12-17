<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server;

use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Rest\Input\Dispatcher as InputDispatcher;
use Ibexa\Rest\RequestParser;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

abstract class Controller implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var \Ibexa\Rest\Input\Dispatcher
     */
    protected $inputDispatcher;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    /**
     * @var \Ibexa\Rest\RequestParser
     */
    protected $requestParser;

    /**
     * Repository.
     *
     * @var \Ibexa\Contracts\Core\Repository\Repository
     */
    protected $repository;

    public function setInputDispatcher(InputDispatcher $inputDispatcher)
    {
        $this->inputDispatcher = $inputDispatcher;
    }

    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function setRequestParser(RequestParser $requestParser)
    {
        $this->requestParser = $requestParser;
    }

    /**
     * Extracts the requested media type from $request.
     *
     * @todo refactor, maybe to a REST Request with an accepts('content-type') method
     *
     * @return string
     */
    protected function getMediaType(Request $request)
    {
        foreach ($request->getAcceptableContentTypes() as $mimeType) {
            if (preg_match('(^([a-z0-9-/.]+)\+.*$)', strtolower($mimeType), $matches)) {
                return $matches[1];
            }
        }

        return 'unknown/unknown';
    }
}

class_alias(Controller::class, 'EzSystems\EzPlatformRest\Server\Controller');
