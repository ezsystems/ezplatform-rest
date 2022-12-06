<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Values;

use Ibexa\Rest\Value as RestValue;

final class LanguageList extends RestValue
{
    /** @var \Ibexa\Contracts\Core\Repository\Values\Content\Language[] */
    public array $languages;

    /**
     * @param array<\Ibexa\Contracts\Core\Repository\Values\Content\Language> $languages
     */
    public function __construct(array $languages)
    {
        $this->languages = $languages;
    }
}
