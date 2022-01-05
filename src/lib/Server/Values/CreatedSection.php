<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Contracts\Core\Repository\Values\ValueObject;

/**
 * Struct representing a freshly created section.
 */
class CreatedSection extends ValueObject
{
    /**
     * The created section.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\Section
     */
    public $section;
}

class_alias(CreatedSection::class, 'EzSystems\EzPlatformRest\Server\Values\CreatedSection');
