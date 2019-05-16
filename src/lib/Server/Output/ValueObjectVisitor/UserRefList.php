<?php

/**
 * File containing the UserRefList ValueObjectVisitor class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\Visitor;

/**
 * UserRefList value object visitor.
 */
class UserRefList extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \EzSystems\EzPlatformRest\Output\Visitor $visitor
     * @param \EzSystems\EzPlatformRest\Output\Generator $generator
     * @param \EzSystems\EzPlatformRest\Server\Values\UserRefList $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('UserRefList');
        $visitor->setHeader('Content-Type', $generator->getMediaType('UserRefList'));
        //@todo Needs refactoring, disabling certain headers should not be done this way
        $visitor->setHeader('Accept-Patch', false);

        $generator->startAttribute('href', $data->path);
        $generator->endAttribute('href');

        $generator->startList('User');
        foreach ($data->users as $user) {
            $generator->startObjectElement('User');

            $generator->startAttribute('href', $this->router->generate('ezpublish_rest_loadUser', array('userId' => $user->contentInfo->id)));
            $generator->endAttribute('href');

            $generator->endObjectElement('User');
        }
        $generator->endList('User');

        $generator->endObjectElement('UserRefList');
    }
}
