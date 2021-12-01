<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;

/**
 * VersionList value object visitor.
 */
class VersionList extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Server\Values\VersionList $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('VersionList');
        $visitor->setHeader('Content-Type', $generator->getMediaType('VersionList'));
        //@todo Needs refactoring, disabling certain headers should not be done this way
        $visitor->setHeader('Accept-Patch', false);

        $generator->startAttribute('href', $data->path);
        $generator->endAttribute('href');

        $generator->startList('VersionItem');
        foreach ($data->versions as $version) {
            $generator->startHashElement('VersionItem');

            $generator->startObjectElement('Version');
            $generator->startAttribute(
                'href',
                $this->router->generate(
                    'ezpublish_rest_loadContentInVersion',
                    [
                        'contentId' => $version->getContentInfo()->id,
                        'versionNumber' => $version->versionNo,
                    ]
                )
            );
            $generator->endAttribute('href');
            $generator->endObjectElement('Version');

            $visitor->visitValueObject($version);

            $generator->endHashElement('VersionItem');
        }
        $generator->endList('VersionItem');

        $generator->endObjectElement('VersionList');
    }
}

class_alias(VersionList::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\VersionList');
