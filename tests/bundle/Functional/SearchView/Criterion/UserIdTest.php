<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\Rest\Functional\SearchView\Criterion;

use Ibexa\Tests\Bundle\Rest\Functional\SearchView\SearchCriterionTestCase;

final class UserIdTest extends SearchCriterionTestCase
{
    public function getCriteriaPayloads(): iterable
    {
        return [
            'multiple User IDs' => [
                'json',
                $this->buildJsonCriterionQuery('"UserIdCriterion": "10,14"'),
                2,
            ],
            'single User ID' => [
                'json',
                $this->buildJsonCriterionQuery('"UserIdCriterion": "10"'),
                1,
            ],
        ];
    }
}

class_alias(UserIdTest::class, 'EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView\Criterion\UserIdTest');
