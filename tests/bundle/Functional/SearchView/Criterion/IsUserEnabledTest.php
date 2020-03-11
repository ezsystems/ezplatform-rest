<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView\Criterion;

use EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView\SearchCriterionTestCase;

final class IsUserEnabledTest extends SearchCriterionTestCase
{
    public function getCriteriaPayloads(): array
    {
        return [
            'is user enabled' => [
                'json',
                $this->buildJsonCriterionQuery('"IsUserEnabledCriterion": true'),
                2,
            ],
            'is user disabled' => [
                'json',
                $this->buildJsonCriterionQuery('"IsUserEnabledCriterion": false'),
                0,
            ],
        ];
    }
}
