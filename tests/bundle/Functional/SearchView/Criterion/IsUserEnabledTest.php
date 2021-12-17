<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\Rest\Functional\SearchView\Criterion;

use Ibexa\Tests\Bundle\Rest\Functional\SearchView\SearchCriterionTestCase;

final class IsUserEnabledTest extends SearchCriterionTestCase
{
    public function getCriteriaPayloads(): iterable
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

class_alias(IsUserEnabledTest::class, 'EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView\Criterion\IsUserEnabledTest');
