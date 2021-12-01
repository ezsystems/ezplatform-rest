<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Controller;

use Ibexa\Contracts\Core\Repository\Exceptions\NotImplementedException;
use Ibexa\Contracts\Core\Repository\SearchService;
use Ibexa\Contracts\Core\Repository\Values\Content\Language;
use Ibexa\Contracts\Core\Repository\Values\Content\LocationQuery;
use Ibexa\Rest\Message;
use Ibexa\Rest\Server\Controller;
use Ibexa\Rest\Server\Values;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for Repository Views (Search, mostly).
 */
class Views extends Controller
{
    /**
     * @var \Ibexa\Contracts\Core\Repository\SearchService
     */
    private $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Creates and executes a content view.
     *
     * @return \Ibexa\Rest\Server\Values\RestExecutedView
     */
    public function createView(Request $request)
    {
        $viewInput = $this->inputDispatcher->parse(
            new Message(
                ['Content-Type' => $request->headers->get('Content-Type')],
                $request->getContent()
            )
        );

        if ($viewInput->query instanceof LocationQuery) {
            $method = 'findLocations';
        } else {
            $method = 'findContent';
        }

        $languageFilter = [
            'languages' => null !== $viewInput->languageCode ? [$viewInput->languageCode] : Language::ALL,
            'useAlwaysAvailable' => $viewInput->useAlwaysAvailable ?? true,
        ];
        $query = $viewInput->query->query;
        if (!empty($query->value)) {
            $languageFilter['excludeTranslationsFromAlwaysAvailable'] = false;
        }

        return new Values\RestExecutedView(
            [
                'identifier' => $viewInput->identifier,
                'searchResults' => $this->searchService->$method(
                    $viewInput->query,
                    $languageFilter
                ),
            ]
        );
    }

    /**
     * List content views.
     *
     * @return \Ibexa\Contracts\Core\Repository\Exceptions\NotImplementedException;
     */
    public function listView()
    {
        return new NotImplementedException('ezpublish_rest.controller.content:listView');
    }

    /**
     * Get a content view.
     *
     * @return \Ibexa\Contracts\Core\Repository\Exceptions\NotImplementedException;
     */
    public function getView()
    {
        return new NotImplementedException('ezpublish_rest.controller.content:getView');
    }

    /**
     * Get a content view results.
     *
     * @return \Ibexa\Contracts\Core\Repository\Exceptions\NotImplementedException;
     */
    public function loadViewResults()
    {
        return new NotImplementedException('ezpublish_rest.controller.content:loadViewResults');
    }
}

class_alias(Views::class, 'EzSystems\EzPlatformRest\Server\Controller\Views');
