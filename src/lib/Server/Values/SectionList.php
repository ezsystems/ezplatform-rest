<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

/**
 * Section list view model.
 */
class SectionList extends RestValue
{
    /**
     * Sections.
     *
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\Section[]
     */
    public $sections;

    /**
     * Path used to load the list of sections.
     *
     * @var string
     */
    public $path;

    /**
     * Construct.
     *
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Section[] $sections
     * @param string $path
     */
    public function __construct(array $sections, $path)
    {
        $this->sections = $sections;
        $this->path = $path;
    }
}

class_alias(SectionList::class, 'EzSystems\EzPlatformRest\Server\Values\SectionList');
