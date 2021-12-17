<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType as APIContentType;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\Visitor;
use Ibexa\Rest\Server\Values;

/**
 * RestContentType value object visitor.
 */
class RestContentType extends RestContentTypeBase
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Server\Values\RestContentType $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $contentType = $data->contentType;

        $urlTypeSuffix = $this->getUrlTypeSuffix($contentType->status);
        $mediaType = $data->fieldDefinitions !== null ? 'ContentType' : 'ContentTypeInfo';

        $generator->startObjectElement($mediaType);

        $visitor->setHeader('Content-Type', $generator->getMediaType($mediaType));

        if ($contentType->status === APIContentType::STATUS_DRAFT) {
            $visitor->setHeader('Accept-Patch', $generator->getMediaType('ContentTypeUpdate'));
        }

        $generator->startAttribute(
            'href',
            $this->router->generate(
                'ezpublish_rest_loadContentType' . $urlTypeSuffix,
                [
                    'contentTypeId' => $contentType->id,
                ]
            )
        );
        $generator->endAttribute('href');

        $generator->startValueElement('id', $contentType->id);
        $generator->endValueElement('id');

        $generator->startValueElement('status', $this->serializeStatus($contentType->status));
        $generator->endValueElement('status');

        $generator->startValueElement('identifier', $contentType->identifier);
        $generator->endValueElement('identifier');

        $this->visitNamesList($generator, $contentType->getNames());

        $descriptions = $contentType->getDescriptions();
        if (is_array($descriptions)) {
            $this->visitDescriptionsList($generator, $descriptions);
        }

        $generator->startValueElement('creationDate', $contentType->creationDate->format('c'));
        $generator->endValueElement('creationDate');

        $generator->startValueElement('modificationDate', $contentType->modificationDate->format('c'));
        $generator->endValueElement('modificationDate');

        $generator->startObjectElement('Creator', 'User');
        $generator->startAttribute(
            'href',
            $this->router->generate(
                'ezpublish_rest_loadUser',
                ['userId' => $contentType->creatorId]
            )
        );
        $generator->endAttribute('href');
        $generator->endObjectElement('Creator');

        $generator->startObjectElement('Modifier', 'User');
        $generator->startAttribute(
            'href',
            $this->router->generate(
                'ezpublish_rest_loadUser',
                ['userId' => $contentType->modifierId]
            )
        );
        $generator->endAttribute('href');
        $generator->endObjectElement('Modifier');

        $generator->startObjectElement('Groups', 'ContentTypeGroupRefList');
        $generator->startAttribute(
            'href',
            $this->router->generate(
                'ezpublish_rest_loadGroupsOfContentType',
                ['contentTypeId' => $contentType->id]
            )
        );
        $generator->endAttribute('href');
        $generator->endObjectElement('Groups');

        $generator->startObjectElement('Draft', 'ContentType');
        $generator->startAttribute(
            'href',
            $this->router->generate(
                'ezpublish_rest_loadContentTypeDraft',
                ['contentTypeId' => $contentType->id]
            )
        );
        $generator->endAttribute('href');
        $generator->endObjectElement('Draft');

        $generator->startValueElement('remoteId', $contentType->remoteId);
        $generator->endValueElement('remoteId');

        $generator->startValueElement('urlAliasSchema', $contentType->urlAliasSchema);
        $generator->endValueElement('urlAliasSchema');

        $generator->startValueElement('nameSchema', $contentType->nameSchema);
        $generator->endValueElement('nameSchema');

        $generator->startValueElement(
            'isContainer',
            $this->serializeBool($generator, $contentType->isContainer)
        );
        $generator->endValueElement('isContainer');

        $generator->startValueElement('mainLanguageCode', $contentType->mainLanguageCode);
        $generator->endValueElement('mainLanguageCode');

        $generator->startValueElement(
            'defaultAlwaysAvailable',
            $this->serializeBool($generator, $contentType->defaultAlwaysAvailable)
        );
        $generator->endValueElement('defaultAlwaysAvailable');

        $generator->startValueElement('defaultSortField', $this->serializeSortField($contentType->defaultSortField));
        $generator->endValueElement('defaultSortField');

        $generator->startValueElement('defaultSortOrder', $this->serializeSortOrder($contentType->defaultSortOrder));
        $generator->endValueElement('defaultSortOrder');

        if ($data->fieldDefinitions !== null) {
            $visitor->visitValueObject(
                new Values\FieldDefinitionList(
                    $contentType,
                    $data->fieldDefinitions
                )
            );
        }

        $generator->endObjectElement($mediaType);
    }
}

class_alias(RestContentType::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\RestContentType');
