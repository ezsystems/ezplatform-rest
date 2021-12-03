<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\Visitor;

/**
 * CreatedContentType value object visitor.
 *
 * @todo coverage add test
 */
class CreatedContentType extends RestContentType
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Server\Values\CreatedContentType $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $restContentType = $data->contentType;

        parent::visit($visitor, $generator, $restContentType);
        $visitor->setHeader(
            'Location',
            $this->router->generate(
                'ezpublish_rest_loadContentType' . $this->getUrlTypeSuffix($restContentType->contentType->status),
                [
                    'contentTypeId' => $restContentType->contentType->id,
                ]
            )
        );
        $visitor->setStatus(201);
    }
}

class_alias(CreatedContentType::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\CreatedContentType');
