<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView\Criterion;

use EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView\SearchCriterionTestCase;

final class UserIdTest extends SearchCriterionTestCase
{
    public function getCriteriaPayloads(): array
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
