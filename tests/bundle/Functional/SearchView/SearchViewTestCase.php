<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView;

use EzSystems\EzPlatformRestBundle\Tests\Functional\TestCase as RESTFunctionalTestCase;

/**
 * @internal for internal use by eZ Platform REST test framework
 */
abstract class SearchViewTestCase extends RESTFunctionalTestCase
{
    /**
     * Perform search View Query providing payload ($body) in a given $format.
     *
     * @param string $format xml or json
     */
    protected function getQueryResultsCount(string $format, string $body): int
    {
        $request = $this->createHttpRequest(
            'POST',
            '/api/ezp/v2/views',
            "ViewInput+{$format}; version=1.1",
            'View+json',
            $body
        );
        $response = $this->sendHttpRequest($request);

        self::assertHttpResponseCodeEquals($response, 200);
        $jsonResponse = json_decode($response->getBody()->getContents());

        if (isset($jsonResponse->ErrorMessage)) {
            self::fail(var_export($jsonResponse, true));
        }

        return $jsonResponse->View->Result->count;
    }
}
