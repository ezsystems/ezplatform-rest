<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRestBundle\Tests\Functional;

use EzSystems\EzPlatformRestBundle\Tests\Functional\TestCase as RESTFunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

final class ImageVariationTest extends RESTFunctionalTestCase
{
    public function testCreateContent(): string
    {
        $string = $this->addTestSuffix(__FUNCTION__);
        $fileName = '1px.png';
        $fileSize = 4718;
        $fileData = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
        $restPrefixPath = '/api/ezp/v2';
        $body = <<< XML
<?xml version="1.0" encoding="UTF-8"?>
<ContentCreate>
  <ContentType href="{$restPrefixPath}/content/types/5" />
  <mainLanguageCode>eng-GB</mainLanguageCode>
  <LocationCreate>
    <ParentLocation href="{$restPrefixPath}/content/locations/1/2" />
    <priority>0</priority>
    <hidden>false</hidden>
    <sortField>PATH</sortField>
    <sortOrder>ASC</sortOrder>
  </LocationCreate>
  <Section href="{$restPrefixPath}/content/sections/3" />
  <alwaysAvailable>true</alwaysAvailable>
  <remoteId>{$string}</remoteId>
  <User href="{$restPrefixPath}/user/users/14" />
  <modificationDate>2012-09-30T12:30:00</modificationDate>
  <fields>
    <field>
      <fieldDefinitionIdentifier>name</fieldDefinitionIdentifier>
      <languageCode>eng-GB</languageCode>
      <fieldValue>{$string}</fieldValue>
    </field>
    <field>
      <fieldDefinitionIdentifier>image</fieldDefinitionIdentifier>
      <languageCode>eng-GB</languageCode>
      <fieldValue>
            <value key="fileName">{$fileName}</value>
            <value key="fileSize">{$fileSize}</value>
            <value key="data">{$fileData}</value>
      </fieldValue>
    </field>
  </fields>
</ContentCreate>
XML;
        $request = $this->createHttpRequest(
            'POST',
            $restPrefixPath . '/content/objects',
            'ContentCreate+xml',
            'ContentInfo+json',
            $body
        );

        $response = $this->sendHttpRequest($request);

        $this->assertHttpResponseCodeEquals($response, Response::HTTP_CREATED);
        $this->assertHttpResponseHasHeader($response, 'Location');
        $this->assertHttpResponseHasHeader($response, 'content-type', 'application/vnd.ez.api.ContentInfo+json');

        $href = $response->getHeader('Location')[0];
        $this->addCreatedElement($href);

        $contentInfo = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        return sprintf(
            '%s/%d', $contentInfo['Content']['Versions']['_href'], $contentInfo['Content']['currentVersionNo']
        );
    }

    /**
     * @depends testCreateContent
     */
    public function testPublishContent(string $restContentHref): string
    {
        $response = $this->sendHttpRequest(
            $this->createHttpRequest('PUBLISH', $restContentHref)
        );
        $this->assertHttpResponseCodeEquals($response, Response::HTTP_NO_CONTENT);

        return $restContentHref;
    }

    /**
     * @depends testPublishContent
     */
    public function testGetImageVariation(string $restVersionHref): void
    {
        $imageResponse = $this->sendHttpRequest(
            $this->createHttpRequest(
                'GET',
                $restVersionHref,
                '',
                'Version+json'
            )
        );

        $jsonResponse = json_decode((string)$imageResponse->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $imageField = $jsonResponse['Version']['Fields']['field'][2];

        self::assertArrayHasKey('variations', $imageField['fieldValue']);

        $variationUrl = $imageField['fieldValue']['variations']['medium']['href'];

        $variationResponse = $this->sendHttpRequest(
            $this->createHttpRequest(
                'GET',
                $variationUrl,
                '',
                'Version+json'
            )
        );
        self::assertHttpResponseCodeEquals($variationResponse, Response::HTTP_OK);
        self::assertHttpResponseHasHeader(
            $variationResponse,
            'content-type',
            'application/vnd.ez.api.ContentImageVariation+json'
        );
    }
}
