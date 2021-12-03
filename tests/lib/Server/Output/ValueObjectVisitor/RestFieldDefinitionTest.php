<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Core\Repository\Values;
use Ibexa\Rest\Output\FieldTypeSerializer;
use Ibexa\Rest\Server;
use Ibexa\Rest\Server\Output\ValueObjectVisitor;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;

class RestFieldDefinitionTest extends ValueObjectVisitorBaseTest
{
    protected $fieldTypeSerializerMock;

    public function setUp(): void
    {
        $this->fieldTypeSerializerMock = $this->createMock(FieldTypeSerializer::class);
    }

    /**
     * @return \DOMDocument
     */
    public function testVisitRestFieldDefinition()
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $restFieldDefinition = $this->getBasicRestFieldDefinition();

        $this->fieldTypeSerializerMock->expects($this->once())
            ->method('serializeFieldDefaultValue')
            ->with(
                $this->isInstanceOf('\\EzSystems\\EzPlatformRest\\Output\\Generator'),
                $this->equalTo('my-field-type'),
                $this->equalTo(
                    'my default value text'
                )
            );

        $this->addRouteExpectation(
            'ezpublish_rest_loadContentTypeFieldDefinition',
            [
                'contentTypeId' => $restFieldDefinition->contentType->id,
                'fieldDefinitionId' => $restFieldDefinition->fieldDefinition->id,
            ],
            "/content/types/{$restFieldDefinition->contentType->id}/fieldDefinitions/{$restFieldDefinition->fieldDefinition->id}"
        );

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $restFieldDefinition
        );

        $result = $generator->endDocument(null);

        $this->assertNotNull($result);

        $dom = new \DOMDocument();
        $dom->loadXml($result);

        return $dom;
    }

    protected function getBasicRestFieldDefinition()
    {
        return new Server\Values\RestFieldDefinition(
            new Values\ContentType\ContentType(
                [
                    'id' => 'contentTypeId',
                    'status' => Values\ContentType\ContentType::STATUS_DEFINED,
                    'fieldDefinitions' => [],
                ]
            ),
            new Values\ContentType\FieldDefinition(
                [
                    'id' => 'fieldDefinitionId_23',
                    'fieldSettings' => ['setting' => 'foo'],
                    'validatorConfiguration' => ['validator' => 'bar'],
                    'identifier' => 'title',
                    'fieldGroup' => 'abstract-field-group',
                    'position' => 2,
                    'fieldTypeIdentifier' => 'my-field-type',
                    'isTranslatable' => true,
                    'isRequired' => false,
                    'isSearchable' => true,
                    'isInfoCollector' => false,
                    'defaultValue' => 'my default value text',
                    'names' => ['eng-US' => 'Sindelfingen'],
                    'descriptions' => ['eng-GB' => 'Bielefeld'],
                ]
            )
        );
    }

    public function provideXpathAssertions()
    {
        $xpathAssertions = [
            '/FieldDefinition[@href="/content/types/contentTypeId/fieldDefinitions/fieldDefinitionId_23"]',
            '/FieldDefinition[@media-type="application/vnd.ez.api.FieldDefinition+xml"]',
            '/FieldDefinition/id[text()="fieldDefinitionId_23"]',
            '/FieldDefinition/identifier[text()="title"]',
            '/FieldDefinition/fieldType[text()="my-field-type"]',
            '/FieldDefinition/fieldGroup[text()="abstract-field-group"]',
            '/FieldDefinition/position[text()="2"]',
            '/FieldDefinition/isTranslatable[text()="true"]',
            '/FieldDefinition/isRequired[text()="false"]',
            '/FieldDefinition/isInfoCollector[text()="false"]',
            '/FieldDefinition/isSearchable[text()="true"]',
            '/FieldDefinition/names',
            '/FieldDefinition/names/value[@languageCode="eng-US" and text()="Sindelfingen"]',
            '/FieldDefinition/descriptions/value[@languageCode="eng-GB" and text()="Bielefeld"]',
        ];

        return array_map(
            static function ($xpath) {
                return [$xpath];
            },
            $xpathAssertions
        );
    }

    /**
     * @param string $xpath
     * @param \DOMDocument $dom
     *
     * @depends testVisitRestFieldDefinition
     * @dataProvider provideXpathAssertions
     */
    public function testGeneratedXml($xpath, \DOMDocument $dom)
    {
        $this->assertXPath($dom, $xpath);
    }

    /**
     * Get the Content visitor.
     *
     * @return \Ibexa\Rest\Server\Output\ValueObjectVisitor\RestFieldDefinition
     */
    protected function internalGetVisitor()
    {
        return new ValueObjectVisitor\RestFieldDefinition($this->fieldTypeSerializerMock);
    }
}

class_alias(RestFieldDefinitionTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\RestFieldDefinitionTest');
