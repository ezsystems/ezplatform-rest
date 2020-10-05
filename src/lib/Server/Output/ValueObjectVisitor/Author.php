<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Visitor;

final class Author extends ValueObjectVisitor
{
    /**
     * @var \eZ\Publish\Core\FieldType\Author\Author
     */
    public function visit(Visitor $visitor, Generator $generator, $data): void
    {
        $generator->startObjectElement('Author');
        $generator->valueElement('name', $data->name);
        $generator->valueElement('email', $data->email);
        $generator->endObjectElement('Author');
    }
}
