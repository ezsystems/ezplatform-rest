<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\Visitor;

/**
 * ContentTypeGroupRefList value object visitor.
 */
class ContentTypeGroupRefList extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \EzSystems\EzPlatformRest\Output\Visitor $visitor
     * @param \EzSystems\EzPlatformRest\Output\Generator $generator
     * @param \EzSystems\EzPlatformRest\Server\Values\ContentTypeGroupRefList $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('ContentTypeGroupRefList');
        $visitor->setHeader('Content-Type', $generator->getMediaType('ContentTypeGroupRefList'));

        $generator->startAttribute(
            'href',
            $this->router->generate(
                'ezpublish_rest_listContentTypesForGroup',
                [
                    'contentTypeGroupId' => $data->contentType->id,
                ]
            )
        );
        $generator->endAttribute('href');

        $groupCount = count($data->contentTypeGroups);

        $generator->startList('ContentTypeGroupRef');
        foreach ($data->contentTypeGroups as $contentTypeGroup) {
            $generator->startObjectElement('ContentTypeGroupRef', 'ContentTypeGroup');

            $generator->startAttribute(
                'href',
                $this->router->generate(
                    'ezpublish_rest_loadContentTypeGroup',
                    [
                        'contentTypeGroupId' => $contentTypeGroup->id,
                    ]
                )
            );
            $generator->endAttribute('href');

            // Unlinking last group is not allowed
            if ($groupCount > 1) {
                $generator->startHashElement('unlink');

                $generator->startAttribute(
                    'href',
                    $this->router->generate(
                        'ezpublish_rest_unlinkContentTypeFromGroup',
                        [
                            'contentTypeId' => $data->contentType->id,
                            'contentTypeGroupId' => $contentTypeGroup->id,
                        ]
                    )
                );
                $generator->endAttribute('href');

                $generator->startAttribute('method', 'DELETE');
                $generator->endAttribute('method');

                $generator->endHashElement('unlink');
            }

            $generator->endObjectElement('ContentTypeGroupRef');
        }
        $generator->endList('ContentTypeGroupRef');

        $generator->endObjectElement('ContentTypeGroupRefList');
    }
}
