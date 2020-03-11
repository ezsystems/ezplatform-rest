<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView\Criterion;

use EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView\SearchCriterionTestCase;

final class SectionIdentifierTest extends SearchCriterionTestCase
{
    public function getCriteriaPayloads(): array
    {
        return [
            'a single Section' => [
                'json',
                $this->buildJsonCriterionQuery('"SectionIdentifierCriterion": "users"'),
                // 2 users + 5 groups
                7,
            ],
            'multiple Sections' => [
                'json',
                $this->buildJsonCriterionQuery('"SectionIdentifierCriterion": "users,standard"'),
                // 2 users + 5 groups + 1 Home Folder
                8,
            ],
        ];
    }
}
