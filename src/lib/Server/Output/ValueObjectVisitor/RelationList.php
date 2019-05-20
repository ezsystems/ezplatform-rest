<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\Visitor;
use EzSystems\EzPlatformRest\Server\Values\RestRelation as ValuesRestRelation;

/**
 * RelationList value object visitor.
 */
class RelationList extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \EzSystems\EzPlatformRest\Output\Visitor $visitor
     * @param \EzSystems\EzPlatformRest\Output\Generator $generator
     * @param \EzSystems\EzPlatformRest\Server\Values\RelationList $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('Relations', 'RelationList');
        $visitor->setHeader('Content-Type', $generator->getMediaType('RelationList'));

        $path = $data->path;
        if ($path === null) {
            $path = $this->router->generate(
                'ezpublish_rest_loadVersionRelations',
                [
                    'contentId' => $data->contentId,
                    'versionNumber' => $data->versionNo,
                ]
            );
        }

        $generator->startAttribute('href', $path);
        $generator->endAttribute('href');

        $generator->startList('Relation');
        foreach ($data->relations as $relation) {
            $visitor->visitValueObject(new ValuesRestRelation($relation, $data->contentId, $data->versionNo));
        }
        $generator->endList('Relation');

        $generator->endObjectElement('Relations');
    }
}
