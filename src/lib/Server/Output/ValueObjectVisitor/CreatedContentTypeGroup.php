<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\Visitor;

/**
 * CreatedContentTypeGroup value object visitor.
 *
 * @todo coverage add test
 */
class CreatedContentTypeGroup extends ContentTypeGroup
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Server\Values\CreatedContentTypeGroup $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        parent::visit($visitor, $generator, $data->contentTypeGroup);
        $visitor->setHeader(
            'Location',
            $this->router->generate(
                'ezpublish_rest_loadContentTypeGroup',
                ['contentTypeGroupId' => $data->contentTypeGroup->id]
            )
        );
        $visitor->setStatus(201);
    }
}

class_alias(CreatedContentTypeGroup::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\CreatedContentTypeGroup');
