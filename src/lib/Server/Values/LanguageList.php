<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Values;

use EzSystems\EzPlatformRest\Value as RestValue;

final class LanguageList extends RestValue
{
    /** @var \eZ\Publish\API\Repository\Values\Content\Language[] */
    public $languages;

    /**
     * @param array<\eZ\Publish\API\Repository\Values\Content\Language> $languages
     */
    public function __construct(array $languages)
    {
        $this->languages = $languages;
    }
}
