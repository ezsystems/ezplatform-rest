<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\Rest\Functional\SearchView\Criterion;

use Ibexa\Tests\Bundle\Rest\Functional\SearchView\SearchCriterionTestCase;

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

class_alias(SiblingTest::class, 'EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView\Criterion\SiblingTest');
