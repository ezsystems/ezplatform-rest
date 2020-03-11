<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView\Criterion;

use EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView\SearchCriterionTestCase;

final class UserEmailTest extends SearchCriterionTestCase
{
    public function getCriteriaPayloads(): array
    {
        return [
            'exact match' => [
                'json',
                $this->buildJsonCriterionQuery('"UserEmailCriterion": "nospam@ez.no"'),
                2,
            ],
            'pattern match' => [
                'json',
                $this->buildJsonCriterionQuery('"UserEmailCriterion": "nospam@*"'),
                2,
            ],
        ];
    }
}
