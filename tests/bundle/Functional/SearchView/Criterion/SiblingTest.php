<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView\Criterion;

use EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView\SearchCriterionTestCase;

final class SiblingTest extends SearchCriterionTestCase
{
    public function getCriteriaPayloads(): iterable
    {
        yield 'sibling' => [
            'json',
            $this->buildJsonCriterionQuery('"SiblingCriterion": 2'),
            2,
        ];
    }
}
