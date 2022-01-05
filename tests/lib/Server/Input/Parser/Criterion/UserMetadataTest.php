<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Input\Parser\Criterion;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\Operator;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\UserMetadata as UserMetadataCriterion;
use Ibexa\Contracts\Rest\Exceptions\Parser;
use Ibexa\Rest\Server\Input\Parser\Criterion\UserMetadata;
use Ibexa\Tests\Rest\Server\Input\Parser\BaseTest;

class UserMetadataTest extends BaseTest
{
    public function testParseProvider()
    {
        return [
            [
                ['UserMetadataCriterion' => ['Target' => 'owner', 'Value' => 14]],
                new UserMetadataCriterion('owner', Operator::IN, [14]),
            ],
            [
                ['UserMetadataCriterion' => ['Target' => 'owner', 'Value' => '14,15,42']],
                new UserMetadataCriterion('owner', Operator::IN, [14, 15, 42]),
            ],
            [
                ['UserMetadataCriterion' => ['Target' => 'owner', 'Value' => [14, 15, 42]]],
                new UserMetadataCriterion('owner', Operator::IN, [14, 15, 42]),
            ],
        ];
    }

    /**
     * Tests the UserMetadata parser.
     *
     * @dataProvider testParseProvider
     */
    public function testParse($data, $expected)
    {
        $userMetadata = $this->getParser();
        $result = $userMetadata->parse($data, $this->getParsingDispatcherMock());

        $this->assertEquals(
            $expected,
            $result,
            'UserMetadata parser not created correctly.'
        );
    }

    /**
     * Test UserMetadata parser throwing exception on invalid UserMetadataCriterion format.
     */
    public function testParseExceptionOnInvalidCriterionFormat()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Invalid <UserMetadataCriterion> format');
        $inputArray = [
            'foo' => 'Michael learns to mock',
        ];

        $dataKeyValueObjectClass = $this->getParser();
        $dataKeyValueObjectClass->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserMetadata parser throwing exception on invalid target format.
     */
    public function testParseExceptionOnInvalidTargetFormat()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Invalid <Target> format');
        $inputArray = [
            'UserMetadataCriterion' => [
                'foo' => 'Mock around the clock',
                'Value' => 42,
            ],
        ];

        $dataKeyValueObjectClass = $this->getParser();
        $dataKeyValueObjectClass->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserMetadata parser throwing exception on invalid value format.
     */
    public function testParseExceptionOnInvalidValueFormat()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Invalid <Value> format');
        $inputArray = [
            'UserMetadataCriterion' => [
                'Target' => 'Moxette',
                'foo' => 42,
            ],
        ];

        $dataKeyValueObjectClass = $this->getParser();
        $dataKeyValueObjectClass->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Test UserMetadata parser throwing exception on wrong type of value format.
     */
    public function testParseExceptionOnWrongValueType()
    {
        $this->expectException(Parser::class);
        $this->expectExceptionMessage('Invalid <Value> format');
        $inputArray = [
            'UserMetadataCriterion' => [
                'Target' => 'We will mock you',
                'Value' => new \stdClass(),
            ],
        ];

        $dataKeyValueObjectClass = $this->getParser();
        $dataKeyValueObjectClass->parse($inputArray, $this->getParsingDispatcherMock());
    }

    /**
     * Returns the UserMetadata criterion parser.
     *
     * @return \Ibexa\Rest\Server\Input\Parser\Criterion\UserMetadata
     */
    protected function internalGetParser()
    {
        return new UserMetadata();
    }
}

class_alias(UserMetadataTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Input\Parser\Criterion\UserMetadataTest');
