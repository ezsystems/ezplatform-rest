<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use eZ\Publish\API\Repository\Values\Content\Thumbnail;
use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\Visitor;
use EzSystems\EzPlatformRest\Output\FieldTypeSerializer;
use EzSystems\EzPlatformRest\Server\Values\RelationList as RelationListValue;
use EzSystems\EzPlatformRest\Server\Values\Version as VersionValue;
use eZ\Publish\API\Repository\Values\ContentType\ContentType;
use eZ\Publish\API\Repository\Values\Content\Field;

/**
 * Version value object visitor.
 */
class Version extends ValueObjectVisitor
{
    /**
     * @var \EzSystems\EzPlatformRest\Output\FieldTypeSerializer
     */
    protected $fieldTypeSerializer;

    /**
     * @param \EzSystems\EzPlatformRest\Output\FieldTypeSerializer $fieldTypeSerializer
     */
    public function __construct(FieldTypeSerializer $fieldTypeSerializer)
    {
        $this->fieldTypeSerializer = $fieldTypeSerializer;
    }

    /**
     * Visit struct returned by controllers.
     *
     * @param \EzSystems\EzPlatformRest\Output\Visitor $visitor
     * @param \EzSystems\EzPlatformRest\Output\Generator $generator
     * @param \EzSystems\EzPlatformRest\Server\Values\Version $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        /** @var \eZ\Publish\Core\Repository\Values\Content\Content $content */
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
     * @param \EzSystems\EzPlatformRest\Output\Generator $generator
     * @param \eZ\Publish\API\Repository\Values\ContentType\ContentType $contentType
     * @param \eZ\Publish\API\Repository\Values\Content\Field $field
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
