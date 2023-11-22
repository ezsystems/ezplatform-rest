<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Controller;

use eZ\Publish\API\Repository\LanguageService;
use eZ\Publish\API\Repository\Values\Content\Language as ApiLanguage;
use EzSystems\EzPlatformRest\Server\Controller as RestController;
use Ibexa\Rest\Server\Values\LanguageList;
use Traversable;

final class Language extends RestController
{
    /** @var \eZ\Publish\API\Repository\LanguageService */
    private $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    public function listLanguages(): LanguageList
    {
        $languages = $this->languageService->loadLanguages();

        if ($languages instanceof Traversable) {
            $languages = iterator_to_array($languages);
        }

        return new LanguageList($languages);
    }

    public function loadLanguage(string $languageCode): ApiLanguage
    {
        return $this->languageService->loadLanguage($languageCode);
    }
}
