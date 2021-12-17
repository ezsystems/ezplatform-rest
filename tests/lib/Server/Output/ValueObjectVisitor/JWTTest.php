<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Rest\Server\Output\ValueObjectVisitor\JWT;
use Ibexa\Rest\Server\Values\JWT as JWTValue;
use Ibexa\Tests\Rest\Output\ValueObjectVisitorBaseTest;

class JWTTest extends ValueObjectVisitorBaseTest
{
    public function testVisit(): string
    {
        $visitor = $this->getVisitor();
        $generator = $this->getGenerator();

        $generator->startDocument(null);

        $token = new JWTValue('abc');

        $visitor->visit(
            $this->getVisitorMock(),
            $generator,
            $token
        );

        $result = $generator->endDocument(null);

        self::assertNotNull($result);

        return $result;
    }

    /**
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsTokenTagWithTokenAttribute(string $result): void
    {
        self::assertXMLTag(
            [
                'tag' => 'JWT',
                'attributes' => [
                    'token' => 'abc',
                ],
            ],
            $result,
            'Missing <JWT> token attributes.',
            false
        );
    }

    /**
     * @param string $result
     *
     * @depends testVisit
     */
    public function testResultContainsTokenTagWithMediaTypeAttribute(string $result): void
    {
        self::assertXMLTag(
            [
                'tag' => 'JWT',
                'attributes' => [
                    'media-type' => 'application/vnd.ez.api.JWT+xml',
                ],
            ],
            $result,
            'Missing <JWT> media-type attribute.',
            false
        );
    }

    protected function internalGetVisitor(): JWT
    {
        return new JWT();
    }
}

class_alias(JWTTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Output\ValueObjectVisitor\JWTTest');
