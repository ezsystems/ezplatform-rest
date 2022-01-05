<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Ibexa\Contracts\Core\Repository\Values\Content\Thumbnail;
use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;
use Ibexa\Rest\Output\FieldTypeSerializer;
use Ibexa\Rest\Server\Values\RelationList as RelationListValue;
use Ibexa\Rest\Server\Values\Version as VersionValue;

/**
 * Version value object visitor.
 */
class Version extends ValueObjectVisitor
{
    /**
     * @var \Ibexa\Rest\Output\FieldTypeSerializer
     */
    protected $fieldTypeSerializer;

    /**
     * @param \Ibexa\Rest\Output\FieldTypeSerializer $fieldTypeSerializer
     */
    public function __construct(FieldTypeSerializer $fieldTypeSerializer)
    {
        $this->fieldTypeSerializer = $fieldTypeSerializer;
    }

    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Server\Values\Version $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        /** @var \Ibexa\Contracts\Core\Repository\Values\Content\Content $content */
        $content = $data->content;

        $generator->startObjectElement('Version');

        $visitor->setHeader('Content-Type', $generator->getMediaType('Version'));
        $visitor->setHeader('Accept-Patch', $generator->getMediaType('VersionUpdate'));

        $this->visitVersionAttributes($visitor, $generator, $data);
        $this->visitThumbnail($visitor, $generator, $content->getThumbnail());

        $generator->endObjectElement('Version');
    }

    /**
     * Visits a single content field and generates its content.
     *
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType $contentType
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Field $field
     */
    public function visitField(Generator $generator, ContentType $contentType, Field $field)
    {
        $generator->startHashElement('field');

        $generator->startValueElement('id', $field->id);
        $generator->endValueElement('id');

        $generator->startValueElement('fieldDefinitionIdentifier', $field->fieldDefIdentifier);
        $generator->endValueElement('fieldDefinitionIdentifier');

        $generator->startValueElement('languageCode', $field->languageCode);
        $generator->endValueElement('languageCode');

        $generator->startValueElement('fieldTypeIdentifier', $field->fieldTypeIdentifier);
        $generator->endValueElement('fieldTypeIdentifier');

        $this->fieldTypeSerializer->serializeFieldValue(
            $generator,
            $contentType,
            $field
        );

        $generator->endHashElement('field');
    }

    protected function visitVersionAttributes(Visitor $visitor, Generator $generator, VersionValue $data)
    {
        $content = $data->content;

        $versionInfo = $content->getVersionInfo();
        $contentType = $data->contentType;

        $path = $data->path;
        if ($path == null) {
            $path = $this->router->generate(
                'ezpublish_rest_loadContentInVersion',
                [
                    'contentId' => $content->id,
                    'versionNumber' => $versionInfo->versionNo,
                ]
            );
        }

        $generator->startAttribute('href', $path);
        $generator->endAttribute('href');

        $visitor->visitValueObject($versionInfo);

        $generator->startHashElement('Fields');
        $generator->startList('field');
        foreach ($content->getFields() as $field) {
            $this->visitField($generator, $contentType, $field);
        }
        $generator->endList('field');
        $generator->endHashElement('Fields');

        $visitor->visitValueObject(
            new RelationListValue(
                $data->relations,
                $content->id,
                $versionInfo->versionNo
            )
        );
    }

    private function visitThumbnail(
        Visitor $visitor,
        Generator $generator,
        ?Thumbnail $thumbnail
    ): void {
        $generator->startObjectElement('Thumbnail');

        if (!empty($thumbnail)) {
            $generator->startValueElement('resource', $thumbnail->resource);
            $generator->endValueElement('resource');

            $generator->startValueElement('width', $thumbnail->width);
            $generator->endValueElement('width');

            $generator->startValueElement('height', $thumbnail->height);
            $generator->endValueElement('height');

            $generator->startValueElement('mimeType', $thumbnail->mimeType);
            $generator->endValueElement('mimeType');
        }

        $generator->endObjectElement('Thumbnail');
    }
}

class_alias(Version::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\Version');
