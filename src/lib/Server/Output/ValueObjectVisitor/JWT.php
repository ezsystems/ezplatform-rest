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

final class JWT extends ValueObjectVisitor
{
    public function visit(Visitor $visitor, Generator $generator, $data): void
    {
        $visitor->setStatus(200);
        $visitor->setHeader('Content-Type', $generator->getMediaType('JWT'));

        $generator->startObjectElement('JWT');
        $generator->startAttribute('token', $data->token);
        $generator->endAttribute('token');
        $generator->endObjectElement('JWT');
    }
}

class_alias(JWT::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\JWT');
