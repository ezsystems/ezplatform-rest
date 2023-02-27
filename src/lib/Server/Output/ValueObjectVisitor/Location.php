<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use eZ\Publish\API\Repository\Exceptions\UnauthorizedException;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Values\Content;
use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\Visitor;
use EzSystems\EzPlatformRest\Server\Values\RestContent as RestContentValue;

/**
 * Location value object visitor.
 */
class Location extends ValueObjectVisitor
{
    /** @var \eZ\Publish\API\Repository\LocationService */
    private $locationService;

    /** @var \eZ\Publish\API\Repository\ContentService */
    private $contentService;

    public function __construct(
        LocationService $locationService,
        ContentService $contentService
    ) {
        $this->locationService = $locationService;
        $this->contentService = $contentService;
    }

    /**
     * Visit struct returned by controllers.
     *
     * @param \EzSystems\EzPlatformRest\Output\Visitor $visitor
     * @param \EzSystems\EzPlatformRest\Output\Generator $generator
     * @param \eZ\Publish\API\Repository\Values\Content\Location $location
     */
    public function visit(Visitor $visitor, Generator $generator, $location)
    {
        $generator->startObjectElement('Location');
        $visitor->setHeader('Content-Type', $generator->getMediaType('Location'));
        $visitor->setHeader('Accept-Patch', $generator->getMediaType('LocationUpdate'));
        $this->visitLocationAttributes($visitor, $generator, $location);
        $generator->endObjectElement('Location');
    }

    /**
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    protected function visitLocationAttributes(
        Visitor $visitor,
        Generator $generator,
        Content\Location $location
    ) {
        $generator->startAttribute(
            'href',
            $this->router->generate(
                'ezpublish_rest_loadLocation',
                ['locationPath' => trim($location->pathString, '/')]
            )
        );
        $generator->endAttribute('href');

        $generator->startValueElement('id', $location->id);
        $generator->endValueElement('id');

        $generator->startValueElement('priority', $location->priority);
        $generator->endValueElement('priority');

        $generator->startValueElement(
            'hidden',
            $this->serializeBool($generator, $location->hidden)
        );
        $generator->endValueElement('hidden');

        $generator->startValueElement(
            'invisible',
            $this->serializeBool($generator, $location->invisible)
        );
        $generator->endValueElement('invisible');

        $generator->startObjectElement('ParentLocation', 'Location');
        if (trim($location->pathString, '/') !== '1') {
            $generator->startAttribute(
                'href',
                $this->router->generate(
                    'ezpublish_rest_loadLocation',
                    [
                        'locationPath' => implode('/', array_slice($location->path, 0, count($location->path) - 1)),
                    ]
                )
            );
            $generator->endAttribute('href');
        }
        $generator->endObjectElement('ParentLocation');

        $generator->startValueElement('pathString', $location->pathString);
        $generator->endValueElement('pathString');

        $generator->startValueElement('depth', $location->depth);
        $generator->endValueElement('depth');

        $generator->startValueElement('childCount', $this->locationService->getLocationChildCount($location));
        $generator->endValueElement('childCount');

        $generator->startValueElement('remoteId', $location->remoteId);
        $generator->endValueElement('remoteId');

        $generator->startObjectElement('Children', 'LocationList');
        $generator->startAttribute(
            'href',
            $this->router->generate(
                'ezpublish_rest_loadLocationChildren',
                [
                    'locationPath' => trim($location->pathString, '/'),
                ]
            )
        );
        $generator->endAttribute('href');
        $generator->endObjectElement('Children');

        $generator->startObjectElement('Content');
        $generator->startAttribute(
            'href',
            $this->router->generate('ezpublish_rest_loadContent', ['contentId' => $location->contentId])
        );
        $generator->endAttribute('href');
        $generator->endObjectElement('Content');

        $generator->startValueElement('sortField', $this->serializeSortField($location->sortField));
        $generator->endValueElement('sortField');

        $generator->startValueElement('sortOrder', $this->serializeSortOrder($location->sortOrder));
        $generator->endValueElement('sortOrder');

        $generator->startObjectElement('UrlAliases', 'UrlAliasRefList');
        $generator->startAttribute(
            'href',
            $this->router->generate(
                'ezpublish_rest_listLocationURLAliases',
                ['locationPath' => trim($location->pathString, '/')]
            )
        );
        $generator->endAttribute('href');
        $generator->endObjectElement('UrlAliases');

        $generator->startObjectElement('ContentInfo', 'ContentInfo');
        $generator->startAttribute(
            'href',
            $this->router->generate(
                'ezpublish_rest_loadContent',
                ['contentId' => $location->contentId]
            )
        );
        $generator->endAttribute('href');

        $content = $location->getContent();
        $contentInfo = $location->getContentInfo();
        $mainLocation = $this->resolveMainLocation($contentInfo, $location);

        $visitor->visitValueObject(new RestContentValue(
                $contentInfo,
                $mainLocation,
                $content,
                $content->getContentType(),
                $this->contentService->loadRelations($content->getVersionInfo())
            )
        );

        $generator->endObjectElement('ContentInfo');
    }

    /**
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    private function resolveMainLocation(
        Content\ContentInfo $contentInfo,
        Content\Location $location
    ): ?Content\Location {
        $mainLocationId = $contentInfo->getMainLocationId();
        if ($mainLocationId === null) {
            return null;
        }

        if ($mainLocationId === $location->id) {
            return $location;
        }

        try {
            return $this->locationService->loadLocation($mainLocationId);
        } catch (UnauthorizedException $e) {
            return null;
        }
    }
}
