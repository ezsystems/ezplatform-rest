<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\Rest\Functional\SearchView;

abstract class SearchCriterionTestCase extends SearchViewTestCase
{
    abstract public function getCriteriaPayloads(): iterable;

    /**
     * @dataProvider getCriteriaPayloads
     */
    public function testFindContent(string $format, string $body, int $expectedItemCount): void
    {
        self::assertEquals(
            $expectedItemCount,
            $this->getQueryResultsCount($format, $body),
            "Expected item count failed for '{$body}'"
        );
    }

    protected function buildJsonCriterionQuery(string $criterionJsonBody): string
    {
        return <<< JSON
            {
                "ViewInput": {
                    "identifier": "your-query-id",
                    "public": "false",
                    "LocationQuery": {
                        "Filter": {
                            $criterionJsonBody
                        },
                        "limit": "10",
                        "offset": "0"
                    }
                }
            }
            JSON;
    }
}

class_alias(SearchCriterionTestCase::class, 'EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView\SearchCriterionTestCase');
