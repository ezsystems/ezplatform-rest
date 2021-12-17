<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\Rest\Functional\SearchView\Criterion;

use Ibexa\Tests\Bundle\Rest\Functional\SearchView\SearchCriterionTestCase;

final class UserEmailTest extends SearchCriterionTestCase
{
    public function getCriteriaPayloads(): iterable
    {
        return [
            'exact match' => [
                'json',
                $this->buildJsonCriterionQuery('"UserEmailCriterion": "admin@link.invalid"'),
                1,
            ],
            'pattern match' => [
                'json',
                $this->buildJsonCriterionQuery('"UserEmailCriterion": "admin@*"'),
                1,
            ],
        ];
    }
}

class_alias(UserEmailTest::class, 'EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView\Criterion\UserEmailTest');
