<?php

/**
 * File containing the UserSessionDeleted class.
 *
 * @copyright Copyright (C) 1999-2014 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 */
namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\Visitor;

class DeletedUserSession extends NoContent
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \EzSystems\EzPlatformRest\Output\Visitor $visitor
     * @param \EzSystems\EzPlatformRest\Output\Generator $generator
     * @param \EzSystems\EzPlatformRest\Server\Values\DeletedUserSession $data
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
