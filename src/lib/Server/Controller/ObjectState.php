<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Controller;

use Ibexa\Contracts\Core\Repository\ContentService;
use Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException;
use Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException;
use Ibexa\Contracts\Core\Repository\ObjectStateService;
use Ibexa\Contracts\Core\Repository\Values\Content\Language;
use Ibexa\Rest\Message;
use Ibexa\Rest\Server\Controller as RestController;
use Ibexa\Rest\Server\Exceptions\ForbiddenException;
use Ibexa\Rest\Server\Values;
use Ibexa\Rest\Values\ContentObjectStates;
use Ibexa\Rest\Values\RestObjectState;
use Symfony\Component\HttpFoundation\Request;

/**
 * ObjectState controller.
 */
class ObjectState extends RestController
{
    /**
     * ObjectState service.
     *
     * @var \Ibexa\Contracts\Core\Repository\ObjectStateService
     */
    protected $objectStateService;

    /**
     * Content service.
     *
     * @var \Ibexa\Contracts\Core\Repository\ContentService
     */
    protected $contentService;

    /**
     * Construct controller.
     *
     * @param \Ibexa\Contracts\Core\Repository\ObjectStateService $objectStateService
     * @param \Ibexa\Contracts\Core\Repository\ContentService $contentService
     */
    public function __construct(ObjectStateService $objectStateService, ContentService $contentService)
    {
        $this->objectStateService = $objectStateService;
        $this->contentService = $contentService;
    }

    /**
     * Creates a new object state group.
     *
     * @throws \Ibexa\Rest\Server\Exceptions\ForbiddenException
     *
     * @return \Ibexa\Rest\Server\Values\CreatedObjectStateGroup
     */
    public function createObjectStateGroup(Request $request)
    {
        try {
            $createdStateGroup = $this->objectStateService->createObjectStateGroup(
                $this->inputDispatcher->parse(
                    new Message(
                        ['Content-Type' => $request->headers->get('Content-Type')],
                        $request->getContent()
                    )
                )
            );
        } catch (InvalidArgumentException $e) {
            throw new ForbiddenException($e->getMessage());
        }

        return new Values\CreatedObjectStateGroup(
            [
                'objectStateGroup' => $createdStateGroup,
            ]
        );
    }

    /**
     * Creates a new object state.
     *
     * @param $objectStateGroupId
     *
     * @throws \Ibexa\Rest\Server\Exceptions\ForbiddenException
     *
     * @return \Ibexa\Rest\Server\Values\CreatedObjectState
     */
    public function createObjectState($objectStateGroupId, Request $request)
    {
        $objectStateGroup = $this->objectStateService->loadObjectStateGroup($objectStateGroupId);

        try {
            $createdObjectState = $this->objectStateService->createObjectState(
                $objectStateGroup,
                $this->inputDispatcher->parse(
                    new Message(
                        ['Content-Type' => $request->headers->get('Content-Type')],
                        $request->getContent()
                    )
                )
            );
        } catch (InvalidArgumentException $e) {
            throw new ForbiddenException($e->getMessage());
        }

        return new Values\CreatedObjectState(
            [
                'objectState' => new RestObjectState(
                    $createdObjectState,
                    $objectStateGroup->id
                ),
            ]
        );
    }

    /**
     * Loads an object state group.
     *
     * @param $objectStateGroupId
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\ObjectState\ObjectStateGroup
     */
    public function loadObjectStateGroup($objectStateGroupId)
    {
        return $this->objectStateService->loadObjectStateGroup($objectStateGroupId, Language::ALL);
    }

    /**
     * Loads an object state.
     *
     * @param $objectStateGroupId
     * @param $objectStateId
     *
     * @return \Ibexa\Rest\Values\RestObjectState
     */
    public function loadObjectState($objectStateGroupId, $objectStateId)
    {
        return new RestObjectState(
            $this->objectStateService->loadObjectState($objectStateId, Language::ALL),
            $objectStateGroupId
        );
    }

    /**
     * Returns a list of all object state groups.
     *
     * @return \Ibexa\Rest\Server\Values\ObjectStateGroupList
     */
    public function loadObjectStateGroups()
    {
        return new Values\ObjectStateGroupList(
            $this->objectStateService->loadObjectStateGroups(0, -1, Language::ALL)
        );
    }

    /**
     * Returns a list of all object states of the given group.
     *
     * @param $objectStateGroupId
     *
     * @return \Ibexa\Rest\Server\Values\ObjectStateList
     */
    public function loadObjectStates($objectStateGroupId)
    {
        $objectStateGroup = $this->objectStateService->loadObjectStateGroup($objectStateGroupId);

        return new Values\ObjectStateList(
            $this->objectStateService->loadObjectStates($objectStateGroup, Language::ALL),
            $objectStateGroup->id
        );
    }

    /**
     * The given object state group including the object states is deleted.
     *
     * @param $objectStateGroupId
     *
     * @return \Ibexa\Rest\Server\Values\NoContent
     */
    public function deleteObjectStateGroup($objectStateGroupId)
    {
        $this->objectStateService->deleteObjectStateGroup(
            $this->objectStateService->loadObjectStateGroup($objectStateGroupId)
        );

        return new Values\NoContent();
    }

    /**
     * The given object state is deleted.
     *
     * @param $objectStateId
     *
     * @return \Ibexa\Rest\Server\Values\NoContent
     */
    public function deleteObjectState($objectStateId)
    {
        $this->objectStateService->deleteObjectState(
            $this->objectStateService->loadObjectState($objectStateId)
        );

        return new Values\NoContent();
    }

    /**
     * Updates an object state group.
     *
     * @param $objectStateGroupId
     *
     * @throws \Ibexa\Rest\Server\Exceptions\ForbiddenException
     *
     * @return \Ibexa\Contracts\Core\Repository\Values\ObjectState\ObjectStateGroup
     */
    public function updateObjectStateGroup($objectStateGroupId, Request $request)
    {
        $updateStruct = $this->inputDispatcher->parse(
            new Message(
                ['Content-Type' => $request->headers->get('Content-Type')],
                $request->getContent()
            )
        );

        $objectStateGroup = $this->objectStateService->loadObjectStateGroup($objectStateGroupId);

        try {
            $updatedStateGroup = $this->objectStateService->updateObjectStateGroup($objectStateGroup, $updateStruct);

            return $updatedStateGroup;
        } catch (InvalidArgumentException $e) {
            throw new ForbiddenException($e->getMessage());
        }
    }

    /**
     * Updates an object state.
     *
     * @param $objectStateGroupId
     * @param $objectStateId
     *
     * @throws \Ibexa\Rest\Server\Exceptions\ForbiddenException
     *
     * @return \Ibexa\Rest\Values\RestObjectState
     */
    public function updateObjectState($objectStateGroupId, $objectStateId, Request $request)
    {
        $updateStruct = $this->inputDispatcher->parse(
            new Message(
                ['Content-Type' => $request->headers->get('Content-Type')],
                $request->getContent()
            )
        );

        $objectState = $this->objectStateService->loadObjectState($objectStateId);

        try {
            $updatedObjectState = $this->objectStateService->updateObjectState($objectState, $updateStruct);

            return new RestObjectState($updatedObjectState, $objectStateGroupId);
        } catch (InvalidArgumentException $e) {
            throw new ForbiddenException($e->getMessage());
        }
    }

    /**
     * Returns the object states of content.
     *
     * @param $contentId
     *
     * @return \Ibexa\Rest\Values\ContentObjectStates
     */
    public function getObjectStatesForContent($contentId)
    {
        $groups = $this->objectStateService->loadObjectStateGroups();
        $contentInfo = $this->contentService->loadContentInfo($contentId);

        $contentObjectStates = [];

        foreach ($groups as $group) {
            try {
                $state = $this->objectStateService->getContentState($contentInfo, $group);
                $contentObjectStates[] = new RestObjectState($state, $group->id);
            } catch (NotFoundException $e) {
                // Do nothing
            }
        }

        return new ContentObjectStates($contentObjectStates);
    }

    /**
     * Updates object states of content
     * An object state in the input overrides the state of the object state group.
     *
     * @param $contentId
     *
     * @throws \Ibexa\Rest\Server\Exceptions\ForbiddenException
     *
     * @return \Ibexa\Rest\Values\ContentObjectStates
     */
    public function setObjectStatesForContent($contentId, Request $request)
    {
        $newObjectStates = $this->inputDispatcher->parse(
            new Message(
                ['Content-Type' => $request->headers->get('Content-Type')],
                $request->getContent()
            )
        );

        $countByGroups = [];
        foreach ($newObjectStates as $newObjectState) {
            $groupId = (int)$newObjectState->groupId;
            if (array_key_exists($groupId, $countByGroups)) {
                ++$countByGroups[$groupId];
            } else {
                $countByGroups[$groupId] = 1;
            }
        }

        foreach ($countByGroups as $groupId => $count) {
            if ($count > 1) {
                throw new ForbiddenException("Multiple Object states provided for group with ID $groupId");
            }
        }

        $contentInfo = $this->contentService->loadContentInfo($contentId);

        $contentObjectStates = [];
        foreach ($newObjectStates as $newObjectState) {
            $objectStateGroup = $this->objectStateService->loadObjectStateGroup($newObjectState->groupId);
            $this->objectStateService->setContentState($contentInfo, $objectStateGroup, $newObjectState->objectState);
            $contentObjectStates[(int)$objectStateGroup->id] = $newObjectState;
        }

        return new ContentObjectStates($contentObjectStates);
    }
}

class_alias(ObjectState::class, 'EzSystems\EzPlatformRest\Server\Controller\ObjectState');
