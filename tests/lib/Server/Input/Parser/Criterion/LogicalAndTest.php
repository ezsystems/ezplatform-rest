<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Tests\Server\Input\Parser\Criterion;

use eZ\Publish\API\Repository\Values\Content;
use EzSystems\EzPlatformRest\Exceptions\Parser as ParserException;
use EzSystems\EzPlatformRest\Input\ParsingDispatcher;
use EzSystems\EzPlatformRest\Server\Input\Parser;
use EzSystems\EzPlatformRest\Tests\Server\Input\Parser\BaseTest;

class LogicalAndTest extends BaseTest
{
    /**
     * Logical parsing of AND statement.
     *
     * Notice regarding multiple criteria of same type:
     *
     * The XML decoder of EZ is not creating numeric arrays, instead using the tag as the array key. See
     * variable $logicalAndParsedFromXml. This causes the ContentTypeIdentifierCriterion-Tag to appear as one-element
     * (type numeric array) and two criteria configuration inside. The logical or parser will take care
     * of this and return a flatt LogicalAnd criterion with 3 criteria inside.
     *
     * ```
     * <AND>
     *   <ContentTypeIdentifierCriterion>author</ContentTypeIdentifierCriterion>
     *   <ContentTypeIdentifierCriterion>book</ContentTypeIdentifierCriterion>
     *   <Field>
     *     <name>title</name>
     *     <operator>EQ</operator>
     *     <value>Contributing to projects</value>
     *   </Field>
     * </AND>
     * ```
     */
    public function testParseLogicalAnd()
    {
        $logicalAndParsedFromXml = [
            'AND' => [
                'ContentTypeIdentifierCriterion' => [
                    0 => 'author',
                    1 => 'book',
                ],
                'Field' => [
                    'name' => 'title',
                    'operator' => 'EQ',
                    'value' => 'Contributing to projects',
                ],
            ],
        ];

        $criterionMock = $this->createMock(Content\Query\Criterion::class, [], [], '', false);

        $parserMock = $this->createMock(\EzSystems\EzPlatformRest\Input\Parser::class);
        $parserMock->method('parse')->willReturn($criterionMock);

        $result = $this->internalGetParser()->parse($logicalAndParsedFromXml, new ParsingDispatcher([
            'application/vnd.ez.api.internal.criterion.ContentTypeIdentifier' => $parserMock,
            'application/vnd.ez.api.internal.criterion.Field' => $parserMock,
        ]));

        self::assertInstanceOf(Content\Query\Criterion\LogicalAnd::class, $result);
        self::assertCount(3, (array)$result->criteria);
    }

    public function testThrowsExceptionOnInvalidAndStatement()
    {
        $this->expectException(ParserException::class);
        $this->internalGetParser()->parse(['AND' => 'Should be an array'], new ParsingDispatcher());
    }

    /**
     * @return Parser\Criterion\LogicalAnd
     */
    protected function internalGetParser()
    {
        return new Parser\Criterion\LogicalAnd();
    }
}
