<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\Rest\Functional;

use Ibexa\Tests\Bundle\Rest\Functional\TestCase as RESTFunctionalTestCase;

final class LanguageTest extends RESTFunctionalTestCase
{
    use ResourceAssertionsTrait;

    private const SNAPSHOT_DIR = __DIR__ . '/_snapshot';

    public function testLanguageListJson(): void
    {
        $request = $this->createHttpRequest('GET', '/api/ibexa/v2/languages', '', 'LanguageList+json');
        $response = $this->sendHttpRequest($request);

        self::assertHttpResponseCodeEquals($response, 200);
        $content = $response->getBody()->getContents();
        self::assertJson($content);

        self::assertJsonResponseIsValid($content, 'LanguageList');
        self::assertResponseMatchesJsonSnapshot($content, self::SNAPSHOT_DIR . '/LanguageList.json');
    }

    public function testLanguageListXml(): void
    {
        $request = $this->createHttpRequest('GET', '/api/ibexa/v2/languages');
        $response = $this->sendHttpRequest($request);

        self::assertHttpResponseCodeEquals($response, 200);
        $content = $response->getBody()->getContents();
        self::assertResponseMatchesXmlSnapshot($content, self::SNAPSHOT_DIR . '/LanguageList.xml');
    }

    public function testLanguageViewJson(): void
    {
        $request = $this->createHttpRequest('GET', '/api/ibexa/v2/languages/eng-GB', '', 'LanguageList+json');
        $response = $this->sendHttpRequest($request);

        self::assertHttpResponseCodeEquals($response, 200);
        $content = $response->getBody()->getContents();
        self::assertJson($content);

        self::assertJsonResponseIsValid($content, 'Language');
        self::assertResponseMatchesJsonSnapshot($content, self::SNAPSHOT_DIR . '/Language.json');
    }

    public function testLanguageViewXml(): void
    {
        $request = $this->createHttpRequest('GET', '/api/ibexa/v2/languages/eng-GB');
        $response = $this->sendHttpRequest($request);

        self::assertHttpResponseCodeEquals($response, 200);
        $content = $response->getBody()->getContents();
        self::assertResponseMatchesXmlSnapshot($content, self::SNAPSHOT_DIR . '/Language.xml');
    }
}
