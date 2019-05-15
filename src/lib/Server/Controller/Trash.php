<?php

/**
 * File containing the Trash controller class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Controller;

use EzSystems\EzPlatformRest\Server\Values;
use EzSystems\EzPlatformRest\Server\Controller as RestController;
use eZ\Publish\API\Repository\TrashService;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use EzSystems\EzPlatformRest\Server\Exceptions\ForbiddenException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trash controller.
 */
class Trash extends RestController
{
    /**
     * Trash service.
     *
     * @var \eZ\Publish\API\Repository\TrashService
     */
    protected $trashService;

    /**
     * Location service.
     *
     * @var \eZ\Publish\API\Repository\LocationService
     */
    protected $locationService;

    /**
     * Construct controller.
     *
     * @param \eZ\Publish\API\Repository\TrashService $trashService
     * @param \eZ\Publish\API\Repository\LocationService $locationService
     */
    public function __construct(TrashService $trashService, LocationService $locationService)
    {
        $this->trashService = $trashService;
        $this->locationService = $locationService;
    }

    /**
     * Returns a list of all trash items.
     *
     * @return \EzSystems\EzPlatformRest\Server\Values\Trash
     */
    public function loadTrashItems(Request $request)
    {
        $offset = $request->query->has('offset') ? (int)$request->query->get('offset') : 0;
        $limit = $request->query->has('limit') ? (int)$request->query->get('limit') : -1;

        $query = new Query();
        $query->offset = $offset >= 0 ? $offset : null;
        $query->limit = $limit >= 0 ? $limit : null;

        $trashItems = array();

        foreach ($this->trashService->findTrashItems($query)->items as $trashItem) {
            $trashItems[] = new Values\RestTrashItem(
                $trashItem,
                $this->locationService->getLocationChildCount($trashItem)
            );
        }

        return new Values\Trash(
            $trashItems,
            $request->getPathInfo()
        );
    }

    /**
     * Returns the trash item given by id.
     *
     * @param $trashItemId
     *
     * @return \EzSystems\EzPlatformRest\Server\Values\RestTrashItem
     */
    public function loadTrashItem($trashItemId)
    {
        return new Values\RestTrashItem(
            $trashItem = $this->trashService->loadTrashItem($trashItemId),
            $this->locationService->getLocationChildCount($trashItem)
        );
    }

    /**
     * Empties the trash.
     *
     * @return \EzSystems\EzPlatformRest\Server\Values\NoContent
     */
    public function emptyTrash()
    {
        $this->trashService->emptyTrash();

        return new Values\NoContent();
    }

    /**
     * Deletes the given trash item.
     *
     * @param $trashItemId
     *
     * @return \EzSystems\EzPlatformRest\Server\Values\NoContent
     */
    public function deleteTrashItem($trashItemId)
    {
        $this->trashService->deleteTrashItem(
            $this->trashService->loadTrashItem($trashItemId)
        );

        return new Values\NoContent();
    }

    /**
     * Restores a trashItem.
     *
     * @param $trashItemId
     *
     * @throws \EzSystems\EzPlatformRest\Server\Exceptions\ForbiddenException
     *
     * @return \EzSystems\EzPlatformRest\Server\Values\ResourceCreated
     */
    public function restoreTrashItem($trashItemId, Request $request)
    {
        $requestDestination = null;
        try {
            $requestDestination = $request->headers->get('Destination');
        } catch (InvalidArgumentException $e) {
            // No Destination header
        }

        $parentLocation = null;
        if ($request->headers->has('Destination')) {
            $locationPathParts = explode(
                '/',
                $this->requestParser->parseHref($request->headers->get('Destination'), 'locationPath')
            );

            try {
                $parentLocation = $this->locationService->loadLocation(array_pop($locationPathParts));
            } catch (NotFoundException $e) {
                throw new ForbiddenException($e->getMessage());
            }
        }

        $trashItem = $this->trashService->loadTrashItem($trashItemId);

        if ($requestDestination === null) {
            // If we're recovering under the original location
            // check if it exists, to return "403 Forbidden" in case it does not
            try {
                $this->locationService->loadLocation($trashItem->parentLocationId);
            } catch (NotFoundException $e) {
                throw new ForbiddenException($e->getMessage());
            }
        }

        $location = $this->trashService->recover($trashItem, $parentLocation);

        return new Values\ResourceCreated(
            $this->router->generate(
                'ezpublish_rest_loadLocation',
                array(
                    'locationPath' => trim($location->pathString, '/'),
                )
            )
        );
    }
}
