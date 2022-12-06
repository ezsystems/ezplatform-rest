<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use eZ\Publish\API\Repository\Values\Content\Language as LanguageValue;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Visitor;

final class Language extends ValueObjectVisitor
{
    /**
     * @param \eZ\Publish\API\Repository\Values\Content\Language $data
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
        $generator->attribute(
            'href',
            $this->router->generate(
                'ibexa.rest.languages.view',
                ['languageCode' => $language->getLanguageCode()],
            ),
        );
        $generator->valueElement('languageId', $language->getId());
        $generator->valueElement('languageCode', $language->getLanguageCode());
        $generator->valueElement('name', $language->getName());
    }
}
