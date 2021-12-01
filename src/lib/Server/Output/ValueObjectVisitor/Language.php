<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Core\Repository\Values\Content\Language as LanguageValue;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;

final class Language extends ValueObjectVisitor
{
    /**
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Language $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data): void
    {
        $generator->startObjectElement('Language');
        $visitor->setHeader('Content-Type', $generator->getMediaType('Language'));
        $this->visitLanguageAttributes($visitor, $generator, $data);
        $generator->endObjectElement('Language');
    }

    private function visitLanguageAttributes(Visitor $visitor, Generator $generator, LanguageValue $language): void
    {
        $generator->valueElement('languageId', $language->id);
        $generator->valueElement('languageCode', $language->languageCode);
        $generator->valueElement('name', $language->name);
    }
}

class_alias(Language::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\Language');
