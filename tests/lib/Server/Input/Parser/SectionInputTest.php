<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Input\Parser;

use eZ\Publish\Core\Repository\SectionService;
use Ibexa\Rest\Server\Input\Parser\SectionInput;
use eZ\Publish\API\Repository\Values\Content\SectionCreateStruct;
use Ibexa\Contracts\Rest\Exceptions\Parser;

class SectionInputTest extends BaseTest
{
    /**
     * Tests the SectionInput parser.
     */
    public function testParse()
    {
        $inputArray = [
            'name' => 'Name Foo',
            'identifier' => 'Identifier Bar',
        ];

        $sectionInput = $this->getParser();
        $result = $sectionInput->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertEquals(
            new SectionCreateStruct($inputArray),
            $result,
            'SectionCreateStruct not created correctly.'
        );
    }

    /**
     * Test SectionInput parser throwing exception on missing identifier.
     */
    public function testParseExceptionOnMissingIdentifier()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'identifier\' attribute for SectionInput.');
        $inputArray = [
            'name' => 'Name Foo',
        ];

        $sectionInput = $this->getParser();
        $sectionInput->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test SectionInput parser throwing exception on missing name.
     */
    public function testParseExceptionOnMissingName()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'name\' attribute for SectionInput.');
        $inputArray = [
            'identifier' => 'Identifier Bar',
        ];

        $sectionInput = $this->getParser();
        $sectionInput->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the section input parser.
     *
     * @return \EzSystems\EzPlatformRest\Server\Input\Parser\SectionInput
     */
    protected function internalGetParser()
    {
        return new SectionInput(
            $this->getSectionServiceMock()
        );
    }

    /**
     * Get the section service mock object.
     *
     * @return \eZ\Publish\API\Repository\SectionService
     */
    protected function getSectionServiceMock()
    {
        $sectionServiceMock = $this->createMock(SectionService::class);

        $sectionServiceMock->expects($this->any())
            ->method('newSectionCreateStruct')
            ->willReturn(
                new SectionCreateStruct()
            );

        return $sectionServiceMock;
    }
}

class_alias(SectionInputTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\SectionInputTest');
