<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\Visitor;

/**
 * CreatedContent value object visitor.
 */
class CreatedContent extends RestContent
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Server\Values\CreatedContent $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        parent::visit($visitor, $generator, $data->content);
        $visitor->setHeader(
            'Location',
            $this->router->generate(
                'ezpublish_rest_loadContent',
                ['contentId' => $data->content->contentInfo->id]
            )
        );
        $visitor->setStatus(201);
    }
}

class_alias(CreatedContent::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\CreatedContent');
