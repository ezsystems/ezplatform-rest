<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;

final class LanguageList extends ValueObjectVisitor
{
    /**
     * @param \Ibexa\Rest\Server\Values\LanguageList $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data): void
    {
        $generator->startObjectElement('LanguageList');
        $visitor->setHeader('Content-Type', $generator->getMediaType('LanguageList'));

        $generator->attribute('href', $this->router->generate('ibexa.rest.languages.list'));

        $generator->startList('Language');
        foreach ($data->languages as $language) {
            $visitor->visitValueObject($language);
        }
        $generator->endList('Language');

        $generator->endObjectElement('LanguageList');
    }
}
