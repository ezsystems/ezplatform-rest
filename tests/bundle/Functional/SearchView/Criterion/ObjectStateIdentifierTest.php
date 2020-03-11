<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView\Criterion;

use EzSystems\EzPlatformRestBundle\Tests\Functional\SearchView\SearchCriterionTestCase;

final class ObjectStateIdentifierTest extends SearchCriterionTestCase
{
    public function getCriteriaPayloads(): array
    {
        return [
            'identifier with target group' => [
                'json',
                $this->buildJsonCriterionQuery('"ObjectStateIdentifierCriterion": {"value": "not_locked", "target": "ez_lock"}'),
                12,
            ],
            'identifier without target group' => [
                'json',
                $this->buildJsonCriterionQuery('"ObjectStateIdentifierCriterion": {"value": "not_locked", "target": null}'),
                12,
            ],
        ];
    }
}
