<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Input\Parser;

use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentTypeGroupCreateStruct;
use Ibexa\Contracts\Rest\Exceptions\Parser;
use Ibexa\Core\Repository\ContentTypeService;
use Ibexa\Rest\Server\Input\Parser\ContentTypeGroupInput;

class ContentTypeGroupInputTest extends BaseTest
{
    /**
     * Tests the ContentTypeGroupInput parser.
     */
    public function testParse()
    {
        $inputArray = [
            'identifier' => 'Identifier Bar',
            'User' => [
                '_href' => '/user/users/14',
            ],
            'modificationDate' => '2012-12-31T12:00:00',
        ];

        $contentTypeGroupInput = $this->getParser();
        $result = $contentTypeGroupInput->parse($inputArray, $this->getParsingDispatcherMock());

        $this->assertInstanceOf(
            ContentTypeGroupCreateStruct::class,
            $result,
            'ContentTypeGroupCreateStruct not created correctly.'
        );

        $this->assertEquals(
            'Identifier Bar',
            $result->identifier,
            'ContentTypeGroupCreateStruct identifier property not created correctly.'
        );

        $this->assertEquals(
            14,
            $result->creatorId,
            'ContentTypeGroupCreateStruct creatorId property not created correctly.'
        );

        $this->assertEquals(
            new \DateTime('2012-12-31T12:00:00'),
            $result->creationDate,
            'ContentTypeGroupCreateStruct creationDate property not created correctly.'
        );
    }

    /**
     * Test ContentTypeGroupInput parser throwing exception on invalid User.
     */
    public function testParseExceptionOnInvalidUser()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Missing \'_href\' attribute for the User element in ContentTypeGroupInput.');
        $inputArray = [
            'identifier' => 'Identifier Bar',
            'User' => [],
            'modificationDate' => '2012-12-31T12:00:00',
        ];

        $contentTypeGroupInput = $this->getParser();
        $contentTypeGroupInput->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the content type group input parser.
     *
     * @return \Ibexa\Rest\Server\Input\Parser\ContentTypeGroupInput
     */
    protected function internalGetParser()
    {
        return new ContentTypeGroupInput(
            $this->getContentTypeServiceMock(),
            $this->getParserTools()
        );
    }

    /**
     * Get the content type service mock object.
     *
     * @return \Ibexa\Contracts\Core\Repository\ContentTypeService
     */
    protected function getContentTypeServiceMock()
    {
        $contentTypeServiceMock = $this->createMock(ContentTypeService::class);

        $contentTypeServiceMock->expects($this->any())
            ->method('newContentTypeGroupCreateStruct')
            ->with($this->equalTo('Identifier Bar'))
            ->willReturn(
                new ContentTypeGroupCreateStruct(['identifier' => 'Identifier Bar'])
            );

        return $contentTypeServiceMock;
    }

    public function getParseHrefExpectationsMap()
    {
        return [
            ['/user/users/14', 'userId', 14],
        ];
    }
}

class_alias(ContentTypeGroupInputTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\ContentTypeGroupInputTest');
