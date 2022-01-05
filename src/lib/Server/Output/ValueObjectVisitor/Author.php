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

final class Author extends ValueObjectVisitor
{
    /**
     * @var \Ibexa\Core\FieldType\Author\Author
     */
    public function visit(Visitor $visitor, Generator $generator, $data): void
    {
        $generator->startObjectElement('Author');
        $generator->valueElement('name', $data->name);
        $generator->valueElement('email', $data->email);
        $generator->endObjectElement('Author');
    }
}

class_alias(Author::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\Author');
