<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\Visitor;

/**
 * CreatedFieldDefinition value object visitor.
 *
 * @todo coverage add test
 */
class CreatedFieldDefinition extends RestFieldDefinition
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Server\Values\CreatedFieldDefinition $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $restFieldDefinition = $data->fieldDefinition;

        parent::visit($visitor, $generator, $restFieldDefinition);

        $draftUriPart = $this->getUrlTypeSuffix($restFieldDefinition->contentType->status);
        $visitor->setHeader(
            'Location',
            $this->router->generate(
                "ezpublish_rest_loadContentType{$draftUriPart}FieldDefinition",
                [
                    'contentTypeId' => $restFieldDefinition->contentType->id,
                    'fieldDefinitionId' => $restFieldDefinition->fieldDefinition->id,
                ]
            )
        );
        $visitor->setStatus(201);
    }
}

class_alias(CreatedFieldDefinition::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\CreatedFieldDefinition');
