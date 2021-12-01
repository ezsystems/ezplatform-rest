<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\Rest\Functional\SearchView\Criterion;

use Ibexa\Tests\Bundle\Rest\Functional\SearchView\SearchCriterionTestCase;

final class UserLoginTest extends SearchCriterionTestCase
{
    public function getCriteriaPayloads(): iterable
    {
        return [
            'exact match for multiple user names' => [
                'json',
                $this->buildJsonCriterionQuery('"UserLoginCriterion": "admin,anonymous"'),
                2,
            ],
            'exact match for single username login' => [
                'json',
                $this->buildJsonCriterionQuery('"UserLoginCriterion": "admin"'),
                1,
            ],
            'pattern match' => [
                'json',
                $this->buildJsonCriterionQuery('"UserLoginCriterion": "adm*"'),
                1,
            ],
        ];
    }
}

class_alias(UserLoginTest::class, 'EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView\Criterion\UserLoginTest');
