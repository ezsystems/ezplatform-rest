<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Rest\Server\Output\ValueObjectVisitor;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\Visitor;

class DeletedUserSession extends NoContent
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \Ibexa\Contracts\Rest\Output\Visitor $visitor
     * @param \Ibexa\Contracts\Rest\Output\Generator $generator
     * @param \Ibexa\Rest\Server\Values\DeletedUserSession $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        parent::visit($visitor, $generator, $data);

        $visitorResponse = $visitor->getResponse();
        $visitorResponse->headers->add($data->response->headers->all());
        foreach ($data->response->headers->getCookies() as $cookie) {
            $visitorResponse->headers->setCookie($cookie);
        }
    }
}

class_alias(DeletedUserSession::class, 'EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor\DeletedUserSession');
