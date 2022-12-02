<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Tests\Output\ValueObjectVisitorBaseTest;
use EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\FieldTypeSerializer;
use EzSystems\EzPlatformRest\Server;
use eZ\Publish\Core\Repository\Values;

class RestFieldDefinitionTest extends ValueObjectVisitorBaseTest
{
    protected $fieldTypeSerializerMock;

    public function setUp(): void
    {
        $this->fieldTypeSerializerMock = $this->createMock(FieldTypeSerializer::class);
    }

    public function testVisitRestFieldDefinition(): \DOMDocument
    {
        return $this->generateDomDocument();
    }

    public function testVisitRestFieldDefinitionWithPath(): \DOMDocument
    {
        return $this->generateDomDocument('/content/types/contentTypeId/fieldDefinition/title');
    }

    protected function generateDomDocument(?string $path = null): \DOMDocument
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $restFieldDefinition = $this->getBasicRestFieldDefinition($path);

        $this->fieldTypeSerializerMock->expects($this->once())
            ->method('serializeFieldDefaultValue')
            ->with(
                $this->isInstanceOf('\\EzSystems\\EzPlatformRest\\Output\\Generator'),
                $this->equalTo('my-field-type'),
                $this->equalTo(
                    'my default value text'
                )
            );

        if ($path === null) {
            $this->addRouteExpectation(
                'ezpublish_rest_loadContentTypeFieldDefinition',
                [
                    'contentTypeId' => $restFieldDefinition->contentType->id,
                    'fieldDefinitionId' => $restFieldDefinition->fieldDefinition->id,
                ],
                "/content/types/{$restFieldDefinition->contentType->id}/fieldDefinitions/{$restFieldDefinition->fieldDefinition->id}"
            );
        }

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

    protected function getBasicRestFieldDefinition(?string $path = null): Server\Values\RestFieldDefinition
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
            ),
            $path
        );
    }

    public function provideXpathAssertions(): array
    {
        $xpathAssertions = $this->getXpathAssertions();
        $xpathAssertions[] = '/FieldDefinition[@href="/content/types/contentTypeId/fieldDefinitions/fieldDefinitionId_23"]';

        return array_map(
            function (string $xpath): array {
                return [$xpath];
            },
            $xpathAssertions
        );
    }

    public function provideXpathAssertionsPath(): array
    {
        $xpathAssertions = $this->getXpathAssertions();
        $xpathAssertions[] = '/FieldDefinition[@href="/content/types/contentTypeId/fieldDefinition/title"]';

        return array_map(
            function (string $xpath): array {
                return [$xpath];
            },
            $xpathAssertions
        );
    }

    protected function getXpathAssertions(): array
    {
        return [
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
    }

    /**
     * @depends testVisitRestFieldDefinition
     * @dataProvider provideXpathAssertions
     */
    public function testGeneratedXml(string $xpath, \DOMDocument $dom): void
    {
        $this->assertXPath($dom, $xpath);
    }

    /**
     * @depends testVisitRestFieldDefinitionWithPath
     * @dataProvider provideXpathAssertionsPath
     */
    public function testGeneratedXmlPath(string $xpath, \DOMDocument $dom): void
    {
        $this->assertXPath($dom, $xpath);
    }

    /**
     * Get the Content visitor.
     */
    protected function internalGetVisitor(): ValueObjectVisitor\RestFieldDefinition
    {
        return new ValueObjectVisitor\RestFieldDefinition($this->fieldTypeSerializerMock);
    }
}
